<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable(['name', 'email', 'password', 'is_freelancer', 'is_client', 'bio', 'photo', 'campus', 'skills'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_freelancer' => 'boolean',
            'is_client' => 'boolean',
            'is_admin' => 'boolean', // kolom lama, tidak dipakai lagi (admin sekarang di tabel `admins`)
            'skills' => 'array',
        ];
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Pesanan yang SAYA buat (sebagai pembeli)
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    // Pesanan yang masuk ke jasa milik saya (User -> Service -> Order)
    public function receivedOrders()
    {
        return $this->hasManyThrough(Order::class, Service::class);
    }

    // ---------- Ulasan & rating ----------

    // Ulasan yang DITERIMA user ini sebagai freelancer
    public function reviews()
    {
        return $this->hasMany(Review::class, 'freelancer_id');
    }

    // Rata-rata rating (1 desimal). null kalau belum ada ulasan.
    protected function rating(): Attribute
    {
        return Attribute::get(fn () => $this->reviewStats()['avg']);
    }

    protected function reviewsTotal(): Attribute
    {
        return Attribute::get(fn () => $this->reviewStats()['count']);
    }

    // Satu query untuk rata-rata + jumlah, hasilnya disimpan selama objek ini hidup
    private ?array $reviewStatsCache = null;

    private function reviewStats(): array
    {
        if ($this->reviewStatsCache === null) {
            $row = $this->reviews()->toBase()->selectRaw('count(*) as total, avg(rating) as average')->first();
            $count = (int) ($row->total ?? 0);

            $this->reviewStatsCache = [
                'count' => $count,
                'avg' => $count > 0 ? round((float) $row->average, 1) : null,
            ];
        }

        return $this->reviewStatsCache;
    }

    // ---------- Avatar ----------

    // URL foto profil. Sengaja asset('storage/...'), bukan Storage::url(),
    // supaya tidak bergantung pada APP_URL.
    protected function avatarUrl(): Attribute
    {
        return Attribute::get(fn () => $this->photo ? asset('storage/'.$this->photo) : null);
    }

    protected function initials(): Attribute
    {
        return Attribute::get(fn () => collect(preg_split('/\s+/', trim((string) $this->name)))
            ->filter()->take(2)
            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
            ->implode(''));
    }

    // "Jurusan · Kampus" -> ['Jurusan', 'Kampus'] (string kosong kalau belum diisi)
    public function campusParts(): array
    {
        return array_pad(explode(' · ', (string) $this->campus, 2), 2, '');
    }

    // ---------- Chat ----------

    // Aturan chat (berbasis hubungan pembeli-penjual):
    //  - client boleh menghubungi freelancer mana pun;
    //  - freelancer hanya boleh menghubungi client yang membeli jasanya;
    //  - tidak bisa chat ke diri sendiri.
    public function canStartChatWith(User $other): bool
    {
        if ($other->id === $this->id) {
            return false;
        }

        // Saya berperan sebagai client -> boleh chat ke freelancer
        if ($this->is_client && $other->is_freelancer) {
            return true;
        }

        // Saya freelancer -> boleh chat ke pembeli jasa saya
        return $this->is_freelancer && Order::where('buyer_id', $other->id)
            ->whereHas('service', fn ($q) => $q->where('user_id', $this->id))
            ->exists();
    }

    public function unreadMessagesCount(): int
    {
        return Message::whereNull('read_at')
            ->where('sender_id', '!=', $this->id)
            ->whereHas('conversation', fn ($q) => $q
                ->where('user_one_id', $this->id)
                ->orWhere('user_two_id', $this->id))
            ->count();
    }

    // ---------- Leaderboard ----------

    /**
     * Freelancer terlaris: jumlah order berstatus `selesai` (User -> Service -> Order).
     * Hanya yang punya minimal 1. Nomor peringkat berurutan (1, 2, 3, ...).
     * Kalau jumlahnya sama, yang rating rata-ratanya lebih tinggi ada di atas,
     * lalu nama (A-Z), lalu id. Yang belum punya ulasan dianggap paling bawah di antara skor sama.
     * Sengaja tidak memuat/menampilkan pendapatan.
     */
    public static function topSellers(int $limit = 10): Collection
    {
        $users = static::query()
            ->where('is_freelancer', true)
            ->whereHas('receivedOrders', fn ($q) => $q->where('orders.status', 'selesai')) // minimal 1
            ->withCount(['receivedOrders as completed_orders_count' => fn ($q) => $q->where('orders.status', 'selesai')])
            ->withAvg('reviews as average_rating', 'rating')
            ->orderByDesc('completed_orders_count')
            ->orderByRaw('average_rating is null') // tanpa ulasan -> paling bawah
            ->orderByDesc('average_rating')
            ->orderBy('name')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        foreach ($users as $i => $user) {
            $user->rank = $i + 1;
        }

        return $users;
    }
}
