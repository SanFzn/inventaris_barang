<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// QR Routes (Public)
Route::prefix('qr')->name('qr.')->group(function () {
    Route::get('/generate/{code}', [QrController::class, 'generate'])->where('code', '.*')->name('generate');
});

// Protected Routes - Hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Profile Routes
    Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [AuthController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [AuthController::class, 'deletePhoto'])->name('profile.photo.delete');

    // QR Routes (Protected - semua user)
    Route::prefix('qr')->name('qr.')->group(function () {
        Route::view('/pindai', 'qr.index')->name('index');
    });

    Route::get('/aset', [BarangController::class, 'index'])->name('assets.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('loans.store');
    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'returnItem'])->name('loans.return');
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::post('/aset', [BarangController::class, 'store'])->name('assets.store');
        Route::put('/aset/{barang}', [BarangController::class, 'update'])->name('assets.update');
        Route::delete('/aset/{barang}', [BarangController::class, 'destroy'])->name('assets.destroy');
        Route::get('/persetujuan', [PeminjamanController::class, 'approvals'])->name('approvals.index');
        Route::post('/persetujuan/{peminjaman}/setujui', [PeminjamanController::class, 'approve'])->name('approvals.approve');
        Route::post('/persetujuan/{peminjaman}/tolak', [PeminjamanController::class, 'reject'])->name('approvals.reject');

        // QR Labels
        Route::get('/qr/labels', [BarangController::class, 'labels'])->name('qr.labels');
    });
});
