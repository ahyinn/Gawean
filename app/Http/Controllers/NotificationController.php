<?php

namespace App\Http\Controllers;

use App\Notifications\GaweanNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // Klik notifikasi = tandai terbaca, lalu redirect ke path internal yang tersimpan
    public function open(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $path = $notification->data['path'] ?? null;

        // Cegah open redirect: hanya path internal ("/..." dan bukan "//...")
        if (! GaweanNotification::isSafePath($path)) {
            return redirect()->route('notifications.index');
        }

        return redirect($path);
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    // Untuk polling badge di navbar
    public function counts(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'notifications' => $user->unreadNotifications()->count(),
            'messages' => $user->unreadMessagesCount(),
        ]);
    }
}
