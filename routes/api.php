<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PeminjamanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::post('/auth/register', [ApiAuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);

    Route::get('/barang', [BarangController::class, 'index']);
    Route::get('/barang/{barang}', [BarangController::class, 'show']);
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/{kategori}', [KategoriController::class, 'show']);
    Route::get('/lokasi', [LokasiController::class, 'index']);
    Route::get('/lokasi/{lokasi}', [LokasiController::class, 'show']);
    Route::get('/peminjaman', [PeminjamanController::class, 'index']);
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show']);
    Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'returnItem']);

    Route::middleware('admin')->group(function () {
        Route::apiResource('barang', BarangController::class)->except(['index', 'show']);
        Route::apiResource('kategori', KategoriController::class)->except(['index', 'show']);
        Route::apiResource('lokasi', LokasiController::class)->except(['index', 'show']);
        Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'approve']);
        Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'reject']);
    });
});
