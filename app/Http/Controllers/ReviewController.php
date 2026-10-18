<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Client memberi rating bintang (1-5) + ulasan setelah pesanan selesai.
    // Satu pesanan hanya boleh punya satu ulasan (juga dijaga unique di tabel reviews).
    public function store(Request $request, Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);
        abort_unless($order->status === 'selesai', 403, 'Ulasan hanya bisa diberikan setelah pesanan selesai.');

        if ($order->review()->exists()) {
            return back()->with('error', 'Pesanan ini sudah kamu ulas.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih rating bintang 1 sampai 5.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'comment.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        Review::create([
            'order_id' => $order->id,
            'freelancer_id' => $order->service->user_id,
            'reviewer_id' => auth()->id(),
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()->route('orders.mine')->with('success', 'Terima kasih, ulasanmu tersimpan.');
    }
}
