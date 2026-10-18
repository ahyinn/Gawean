<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator; // BARU
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive(); // BARU

        // Nama hari/bulan dan "x menit yang lalu" dalam bahasa Indonesia
        Carbon::setLocale('id');

        // Angka badge di navbar (notifikasi & chat belum dibaca)
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();

            $view->with('navUnreadNotifications', $user ? $user->unreadNotifications()->count() : 0);
            $view->with('navUnreadMessages', $user ? $user->unreadMessagesCount() : 0);
        });
    }
}