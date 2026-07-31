<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;

/*
|--------------------------------------------------------------------------
| API Routes — OLSHOP FIX
|--------------------------------------------------------------------------
| Semua route di sini dilindungi oleh middleware 'api.token' yang memvalidasi
| Bearer Token dari header Authorization.
|
| Endpoint ini digunakan oleh WMS (wms.listrindo.com) untuk:
|   1. Sinkronisasi stok produk setelah ada pergerakan stok di gudang.
|
*/

Route::middleware(['api.token', 'throttle:60,1'])->prefix('v1')->group(function () {

    // --- SINKRONISASI STOK ---
    // Dipanggil oleh WMS setiap ada pergerakan stok (barang masuk/keluar/adjustment)
    // POST /api/v1/products/sync-stock
    // Body: { "kode_barang": "PRD-001", "total_stock": 50 }
    Route::post('/products/sync-stock', [ApiProductController::class, 'syncStock'])
        ->name('api.products.sync-stock');

});
