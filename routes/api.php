<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\StorefrontController;

/*
|--------------------------------------------------------------------------
| API Routes — OLSHOP FIX
|--------------------------------------------------------------------------
*/

// ── PUBLIK: Diakses oleh Landing Page tanpa token ─────────────────────────
Route::prefix('v1/storefront')->group(function () {
    // GET /api/v1/storefront/products?category=kabel&limit=20
    Route::get('/products', [StorefrontController::class, 'products'])
        ->name('api.storefront.products');
    // GET /api/v1/storefront/categories
    Route::get('/categories', [StorefrontController::class, 'categories'])
        ->name('api.storefront.categories');
});

// ── PRIVAT: Sinkronisasi dari WMS (butuh Bearer Token) ────────────────────
Route::middleware(['api.token', 'throttle:60,1'])->prefix('v1')->group(function () {

    // POST /api/v1/products/sync
    Route::post('/products/sync', [ApiProductController::class, 'syncStock'])
        ->name('api.products.sync');

    // POST /api/v1/products/sync-stock
    Route::post('/products/sync-stock', [ApiProductController::class, 'syncStock'])
        ->name('api.products.sync-stock');

});
