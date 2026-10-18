<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Ulasan dari pembeli untuk satu pesanan yang sudah selesai.
 * Rating freelancer = rata-rata kolom `rating` (lihat User::rating).
 */
class Review extends Model
{
    protected $fillable = ['order_id', 'freelancer_id', 'reviewer_id', 'rating', 'comment'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
