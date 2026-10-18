<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    protected $fillable = ['name', 'slug', 'icon'];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Ikon Bootstrap Icons: pakai kolom `icon` kalau diisi admin, kalau tidak pakai bawaan menurut slug
    public function getIconAttribute(?string $value): string
    {
        if ($value) {
            return $value;
        }

        return [
            'desain-grafis' => 'bi-palette',
            'penyuntingan-video' => 'bi-camera-video',
            'fotografi' => 'bi-camera',
            'pembuatan-presentasi' => 'bi-easel',
            'pengembangan-web' => 'bi-code-slash',
        ][$this->slug] ?? 'bi-grid';
    }
}