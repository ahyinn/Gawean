<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * Satu class notifikasi untuk semua kejadian di Gawean (disimpan di tabel `notifications`).
 * $path adalah path INTERNAL relatif, mis. "/pesanan-masuk" (bukan URL penuh).
 */
class GaweanNotification extends Notification
{
    public function __construct(
        public string $title,
        public string $message,
        public string $path = '/',
        public string $icon = 'bi-bell',
    ) {
        if (! self::isSafePath($this->path)) {
            $this->path = '/';
        }
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'path' => $this->path,
            'icon' => $this->icon,
        ];
    }

    // Path internal yang aman untuk redirect: diawali "/", bukan "//" (protocol-relative),
    // dan tanpa backslash / karakter kontrol ("/\evil.com" dianggap "//evil.com" oleh browser).
    public static function isSafePath(mixed $path): bool
    {
        if (! is_string($path) || $path === '' || $path[0] !== '/') {
            return false;
        }

        if (str_starts_with($path, '//')) {
            return false;
        }

        return ! preg_match('/[\\\\\x00-\x1F\x7F]/', $path);
    }
}
