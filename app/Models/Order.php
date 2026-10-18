<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Status pembayaran (bukan status pekerjaan)
    const PAY_BELUM_BAYAR = 'belum_bayar';
    const PAY_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    const PAY_DITOLAK_VERIFIKASI = 'ditolak_verifikasi';
    const PAY_HELD = 'held';
    const PAY_DICAIRKAN = 'dicairkan';
    const PAY_REFUNDED = 'refunded';

    protected $fillable = [
        'service_id', 'buyer_id', 'price', 'note',
        'status', 'payment_status', 'payment_method', 'payment_proof', 'verification_note',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Ulasan hanya untuk pesanan yang sudah selesai dan belum diulas
    public function canBeReviewed(): bool
    {
        return $this->status === 'selesai' && ! $this->review()->exists();
    }

    public function isHeld(): bool
    {
        return $this->payment_status === self::PAY_HELD;
    }

    public function canUploadProof(): bool
    {
        return in_array($this->payment_status, [self::PAY_BELUM_BAYAR, self::PAY_DITOLAK_VERIFIKASI]);
    }
}