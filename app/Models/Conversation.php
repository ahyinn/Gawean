<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use InvalidArgumentException;

class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where(fn ($q) => $q->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id));
    }

    public function isParticipant(User $user): bool
    {
        return $user->id === $this->user_one_id || $user->id === $this->user_two_id;
    }

    public function otherUser(User $me): User
    {
        return $me->id === $this->user_one_id ? $this->userTwo : $this->userOne;
    }

    // Satu percakapan per pasangan; user_one_id selalu < user_two_id.
    public static function between(User $a, User $b): self
    {
        if ($a->id === $b->id) {
            throw new InvalidArgumentException('Tidak bisa membuat percakapan dengan diri sendiri.');
        }

        $attributes = [
            'user_one_id' => min($a->id, $b->id),
            'user_two_id' => max($a->id, $b->id),
        ];

        try {
            return static::firstOrCreate($attributes);
        } catch (UniqueConstraintViolationException) {
            // Dua request bersamaan: yang kalah balapan tinggal mengambil yang sudah ada.
            return static::where($attributes)->firstOrFail();
        }
    }
}
