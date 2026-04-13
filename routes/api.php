<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProdukApiController;
use App\Http\Controllers\Api\TransaksiApiController;

// ===================
// AUTHENTICATION
// ===================
Route::post('/auth/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/me', [AuthApiController::class, 'profile']);

    // ===================
    // PRODUK (Master Data)
    // ===================
    Route::prefix('master')->group(function () {
        Route::get('/produk', [ProdukApiController::class, 'index']);
        Route::get('/produk/{id}', [ProdukApiController::class, 'show']);
        Route::post('/produk', [ProdukApiController::class, 'store']);
        Route::put('/produk/{id}', [ProdukApiController::class, 'update']);
        Route::delete('/produk/{id}', [ProdukApiController::class, 'destroy']);
    });

    // ===================
    // KASIR & TRANSAKSI
    // ===================
    Route::prefix('kasir')->group(function () {
        Route::get('/katalog', [TransaksiApiController::class, 'produkAktif']); // Produk yang statusnya aktif
        Route::post('/checkout', [TransaksiApiController::class, 'store']);     // Simpan transaksi
        Route::get('/riwayat', [TransaksiApiController::class, 'index']);      // Daftar transaksi
        Route::get('/riwayat/{id}', [TransaksiApiController::class, 'show']);  // Detail transaksi
    });

    // ===================
    // LAPORAN & ANALITIK
    // ===================
    Route::get('/analitik/laporan-penjualan', [TransaksiApiController::class, 'laporan']);
});