<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Desain Grafis', 'Penyuntingan Video', 'Fotografi', 'Pembuatan Presentasi', 'Pengembangan Web'];

        foreach ($names as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }
}