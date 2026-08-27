<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
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
    

// Protected Routes - Hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/aset', [BarangController::class, 'index'])->name('assets.index');
    Route::middleware('admin')->group(function () {
        Route::post('/aset', [BarangController::class, 'store'])->name('assets.store');
        Route::put('/aset/{barang}', [BarangController::class, 'update'])->name('assets.update');
        Route::delete('/aset/{barang}', [BarangController::class, 'destroy'])->name('assets.destroy');
    });
    Route::view('/pindai-qr', 'qr.index')->name('qr.index');
        Route::view('/notifikasi', 'notifications.index')->name('notifications.index');
        Route::view('/cetak-label-qr', 'qr.labels')->name('qr.labels');
    Route::view('/persetujuan', 'approvals.index')->name('approvals.index');
});