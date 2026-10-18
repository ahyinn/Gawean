<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [ServiceController::class, 'index'])->name('home');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

// Profil publik (data asli dari tabel users)
Route::get('/freelancer/{user}', [FreelancerController::class, 'show'])->whereNumber('user')->name('freelancers.show');
Route::get('/klien/{user}', [ClientController::class, 'show'])->whereNumber('user')->name('clients.show');

// Hanya untuk yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Hanya untuk yang SUDAH login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/my-services', [ServiceController::class, 'mine'])->name('services.mine');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::post('/services/{service}/order', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pesanan/{order}/bayar', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/pesanan/{order}/bayar', [OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
    Route::get('/pesanan-saya', [OrderController::class, 'mine'])->name('orders.mine');
    Route::get('/pesanan-masuk', [OrderController::class, 'incoming'])->name('orders.incoming');
    Route::patch('/pesanan/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/pesanan/{order}/ulasan', [ReviewController::class, 'store'])->name('reviews.store');

    // Profil saya
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil/foto', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

    // Chat (polling JSON, tanpa WebSocket)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/mulai/{user}', [ChatController::class, 'start'])->whereNumber('user')->name('chat.start');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->whereNumber('conversation')->name('chat.show');
    Route::get('/chat/{conversation}/pesan', [ChatController::class, 'poll'])->whereNumber('conversation')->name('chat.poll');
    Route::post('/chat/{conversation}/kirim', [ChatController::class, 'send'])
        ->whereNumber('conversation')->middleware('throttle:30,1')->name('chat.send');

    // Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('/notifikasi/{id}', [NotificationController::class, 'open'])->name('notifications.open');
    Route::get('/badge-counts', [NotificationController::class, 'counts'])->name('badges');
});

// ===== Panel Admin: buka lewat /admin (akun admin terpisah, guard `admin`) =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminAuthController::class, 'entry'])->name('home');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(EnsureUserIsAdmin::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::patch('/payments/{order}/verify', [AdminController::class, 'verifyPayment'])->name('payments.verify');
        Route::patch('/payments/{order}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');

        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'categoriesStore'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminController::class, 'categoriesEdit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminController::class, 'categoriesUpdate'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'categoriesDestroy'])->name('categories.destroy');

        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::delete('/services/{service}', [AdminController::class, 'destroyService'])->name('services.destroy');

        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    });
});

// Harus SETELAH /services/create, kalau tidak "create" dianggap sebagai {service}
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
