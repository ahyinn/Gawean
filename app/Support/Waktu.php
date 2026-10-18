<?php

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * Format waktu untuk TAMPILAN. Database tetap menyimpan UTC;
 * di sini dikonversi ke config('app.display_timezone') (default Asia/Jakarta).
 */
class Waktu
{
    public static function lokal(?CarbonInterface $waktu): ?CarbonInterface
    {
        return $waktu?->copy()->timezone(config('app.display_timezone'));
    }

    // Hari ini: "14:05"; hari lain: "11 Okt 14:05"
    public static function chat(?CarbonInterface $waktu): string
    {
        $lokal = self::lokal($waktu);
        if (! $lokal) {
            return '';
        }

        return $lokal->isToday() ? $lokal->format('H:i') : $lokal->translatedFormat('d M H:i');
    }

    // "11 Oktober 2026, 14:05"
    public static function lengkap(?CarbonInterface $waktu): string
    {
        return self::lokal($waktu)?->translatedFormat('d F Y, H:i') ?? '';
    }

    // "5 menit yang lalu"
    public static function relatif(?CarbonInterface $waktu): string
    {
        return $waktu?->diffForHumans() ?? '';
    }
}
