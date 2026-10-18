<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Notifications\GaweanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'freelancers' => User::where('is_freelancer', true)->count(),
            'services' => Service::count(),
            'orders' => Order::count(),
            'pending_payments' => Order::where('payment_status', Order::PAY_MENUNGGU_VERIFIKASI)->count(),
        ];

        $recentOrders = Order::with(['service', 'buyer'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    // ===== Verifikasi Pembayaran =====
    public function payments()
    {
        $pendingPayments = Order::with(['service.user', 'buyer'])
            ->where('payment_status', Order::PAY_MENUNGGU_VERIFIKASI)
            ->oldest()
            ->get();

        return view('admin.payments', compact('pendingPayments'));
    }

    public function verifyPayment(Order $order)
    {
        abort_unless($order->payment_status === Order::PAY_MENUNGGU_VERIFIKASI, 403);

        $order->update([
            'payment_status' => Order::PAY_HELD,
            'verification_note' => null,
        ]);

        $order->loadMissing(['service.user', 'buyer']);

        // Pembayaran terverifikasi -> kabari pembeli DAN freelancer
        $order->buyer->notify(new GaweanNotification(
            'Pembayaran terverifikasi',
            'Pembayaran untuk "'.$order->service->title.'" sudah diverifikasi admin. Pesanan diteruskan ke freelancer.',
            route('orders.mine', [], false),
            'bi-shield-check'
        ));
        $order->service->user->notify(new GaweanNotification(
            'Pesanan siap diproses',
            'Pembayaran '.$order->buyer->name.' untuk "'.$order->service->title.'" sudah diverifikasi. Silakan terima atau tolak pesanan.',
            route('orders.incoming', [], false),
            'bi-bag-check'
        ));

        return back()->with('success', 'Pembayaran diverifikasi. Dana ditahan, menunggu keputusan freelancer.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        abort_unless($order->payment_status === Order::PAY_MENUNGGU_VERIFIKASI, 403);

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update([
            'payment_status' => Order::PAY_DITOLAK_VERIFIKASI,
            'verification_note' => $data['reason'] ?? 'Bukti pembayaran tidak valid atau tidak jelas.',
        ]);

        $order->loadMissing(['service', 'buyer']);

        // Bukti ditolak -> kabari pembeli supaya unggah ulang
        $order->buyer->notify(new GaweanNotification(
            'Bukti pembayaran ditolak',
            'Bukti pembayaran untuk "'.$order->service->title.'" ditolak. Alasan: '.$order->verification_note,
            route('orders.pay', $order, false),
            'bi-x-octagon'
        ));

        return back()->with('success', 'Bukti pembayaran ditolak. Pembeli akan diminta mengunggah ulang.');
    }

    // ===== Pengguna =====
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function destroyUser(User $user)
    {
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    // ===== Kategori =====
    public function categoriesIndex()
    {
        $categories = Category::withCount('services')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function categoriesStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'icon' => ['nullable', 'string', 'max:100', 'regex:/^bi-[a-z0-9-]+$/'],
        ]);

        Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'icon' => $data['icon'] ?? 'bi-briefcase',
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function categoriesEdit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function categoriesUpdate(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'icon' => ['nullable', 'string', 'max:100', 'regex:/^bi-[a-z0-9-]+$/'],
        ]);

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'icon' => $data['icon'] ?? $category->icon,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function categoriesDestroy(Category $category)
    {
        if ($category->services()->exists()) {
            return back()->with('error', 'Kategori "' . $category->name . '" tidak bisa dihapus karena masih dipakai oleh jasa yang ada.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    // ===== Jasa =====
    public function services()
    {
        $services = Service::with(['user', 'category'])->latest()->paginate(15);
        return view('admin.services', compact('services'));
    }

    public function destroyService(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Jasa berhasil dihapus.');
    }

    // ===== Pesanan =====
    public function orders()
    {
        $orders = Order::with(['service.user', 'buyer'])->latest()->paginate(15);
        return view('admin.orders', compact('orders'));
    }
}