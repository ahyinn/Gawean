<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;     // percobaan
    private const DECAY_SECONDS = 60;   // per menit

    public function entry()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'admin-login:'.Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->withErrors(['email' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik."])
                ->onlyInput('email');
        }

        if (! Auth::guard('admin')->attempt($credentials)) {
            RateLimiter::hit($key, self::DECAY_SECONDS);

            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        RateLimiter::clear($key);
        $request->session()->regenerate(); // ganti ID sesi, data sesi user biasa tetap utuh

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        // Hanya melepas login admin. Sengaja TIDAK memakai session()->invalidate(),
        // supaya sesi login user biasa di browser yang sama tidak ikut terhapus.
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('admin.home');
    }
}
