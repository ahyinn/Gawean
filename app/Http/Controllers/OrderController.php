<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Notifications\GaweanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    private const PAYMENT_METHODS = [
        'transfer_bca' => ['label' => 'Transfer Bank BCA', 'icon' => 'bi-bank'],
        'transfer_bri' => ['label' => 'Transfer Bank BRI', 'icon' => 'bi-bank'],
        'qris' => ['label' => 'QRIS', 'icon' => 'bi-qr-code'],
        'ewallet' => ['label' => 'E-Wallet (OVO/GoPay/DANA)', 'icon' => 'bi-wallet2'],
    ];

    // Buat pesanan baru -> lanjut ke halaman pembayaran
    public function store(Request $request, Service $service)
    {
        abort_if($service->user_id === auth()->id(), 403, 'Kamu tidak bisa memesan jasamu sendiri.');

        $data = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = Order::create([
            'service_id' => $service->id,
            'buyer_id' => auth()->id(),
            'price' => $service->price,
            'note' => $data['note'] ?? null,
            'status' => 'menunggu',
            'payment_status' => Order::PAY_BELUM_BAYAR,
        ]);

        // Pesanan baru -> kabari freelancer pemilik jasa
        $service->user->notify(new GaweanNotification(
            'Pesanan baru',
            auth()->user()->name.' memesan jasa "'.$service->title.'". Menunggu pembayaran dan verifikasi admin.',
            route('orders.incoming', [], false),
            'bi-bag-plus'
        ));

        return redirect()->route('orders.pay', $order)
            ->with('success', 'Pesanan dibuat. Silakan lakukan pembayaran dan unggah bukti transfer.');
    }

    // Halaman pembayaran: form upload bukti / status menunggu verifikasi
    public function pay(Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);

        if (in_array($order->payment_status, [Order::PAY_HELD, Order::PAY_DICAIRKAN, Order::PAY_REFUNDED])) {
            return redirect()->route('orders.mine')->with('success', 'Pesanan ini sudah diproses lebih lanjut.');
        }

        return view('orders.pay', [
            'order' => $order->load('service'),
            'methods' => self::PAYMENT_METHODS,
        ]);
    }

    // Kirim bukti pembayaran -> masuk antrean verifikasi admin
    public function confirmPayment(Request $request, Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);
        abort_unless($order->canUploadProof(), 403, 'Bukti pembayaran sudah dikirim dan sedang diproses.');

        $data = $request->validate([
            'payment_method' => ['required', 'in:' . implode(',', array_keys(self::PAYMENT_METHODS))],
            'proof' => ['required', 'image', 'max:4096'],
        ]);

        // Hapus bukti lama kalau ini upload ulang (setelah sebelumnya ditolak admin)
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_method' => $data['payment_method'],
            'payment_proof' => $path,
            'payment_status' => Order::PAY_MENUNGGU_VERIFIKASI,
            'verification_note' => null,
        ]);

        return redirect()->route('orders.mine')->with('success', 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
    }

    // Daftar pesanan yang saya buat (sebagai pembeli)
    public function mine()
    {
        $orders = auth()->user()->orders()->with(['service.user', 'review'])->latest()->get();
        return view('orders.mine', compact('orders'));
    }

    // Daftar pesanan masuk ke jasa milik saya (sebagai freelancer)
    public function incoming()
    {
        abort_unless(auth()->user()->is_freelancer, 403, 'Hanya freelancer yang punya pesanan masuk.');

        $orders = Order::whereHas('service', fn ($q) => $q->where('user_id', auth()->id()))
            ->with(['service', 'buyer'])
            ->latest()
            ->get();

        return view('orders.incoming', compact('orders'));
    }

    // Freelancer terima/tolak pesanan -> HANYA boleh kalau dana sudah "held" (diverifikasi admin)
    public function updateStatus(Request $request, Order $order)
    {
        abort_unless($order->service->user_id === auth()->id(), 403);
        abort_unless($order->isHeld(), 403, 'Pesanan belum diverifikasi pembayarannya oleh admin.');

        $data = $request->validate([
            'status' => ['required', 'in:diterima,ditolak,selesai'],
        ]);

        // Alur: menunggu -> (diterima | ditolak); diterima -> selesai. Selain itu tidak boleh.
        $allowed = [
            'menunggu' => ['diterima', 'ditolak'],
            'diterima' => ['selesai'],
        ];
        abort_unless(
            in_array($data['status'], $allowed[$order->status] ?? [], true),
            403,
            'Perubahan status tidak valid untuk pesanan ini.'
        );

        $order->status = $data['status'];

        // Efek ke status pembayaran mengikuti keputusan freelancer
        if ($data['status'] === 'ditolak') {
            $order->payment_status = Order::PAY_REFUNDED; // dana dikembalikan ke pembeli
        } elseif ($data['status'] === 'selesai') {
            $order->payment_status = Order::PAY_DICAIRKAN; // dana cair ke freelancer
        }

        $order->save();

        // Keputusan freelancer -> kabari pembeli
        $title = $order->service->title;
        [$notifTitle, $notifMessage, $notifIcon] = match ($order->status) {
            'diterima' => ['Pesanan diterima', 'Freelancer menerima pesanan "'.$title.'" dan mulai mengerjakannya.', 'bi-check2-circle'],
            'ditolak' => ['Pesanan ditolak', 'Pesanan "'.$title.'" ditolak freelancer. Dana akan dikembalikan.', 'bi-x-circle'],
            'selesai' => ['Pesanan selesai', 'Pesanan "'.$title.'" sudah selesai dikerjakan.', 'bi-check2-all'],
        };
        $order->buyer->notify(new GaweanNotification(
            $notifTitle,
            $notifMessage,
            route('orders.mine', [], false),
            $notifIcon
        ));

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}