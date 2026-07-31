<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController, ProductController, CategoryController, BrandController,
    OrderController, UserController, FlashSaleController, CouponController,
    ReviewController, BannerController, SellerController, WithdrawalController,
    ReportController, SettingController, AuditLogController, DiscussionController,
    FailedSyncLogController
};

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:super_admin|admin|staff', 'admin.active'])
    ->group(function () {

    // ── DASHBOARD ─────────────────────────────────────────────────
    Route::get('/', function() {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── PRODUK ────────────────────────────────────────────────────
    Route::middleware('permission:products.view')->group(function () {
        Route::resource('products', ProductController::class);
        Route::post('products/bulk', [ProductController::class, 'bulk'])
            ->name('products.bulk');
        Route::post('products/import', [ProductController::class, 'import'])
            ->name('products.import')
            ->middleware('permission:products.create');
        Route::get('products/export', [ProductController::class, 'export'])
            ->name('products.export');
    });

    // ── KATEGORI & MEREK ──────────────────────────────────────────
    Route::middleware('permission:products.view')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::post('categories/reorder', [CategoryController::class, 'reorder'])
            ->name('categories.reorder');
        Route::resource('brands', BrandController::class);
    });

    // ── ORDER ─────────────────────────────────────────────────────
    Route::middleware('permission:orders.view')->group(function () {
        Route::resource('orders', OrderController::class)
            ->only(['index', 'show']);
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.status')
            ->middleware('permission:orders.update-status');
        Route::post('orders/{order}/refund', [OrderController::class, 'refund'])
            ->name('orders.refund')
            ->middleware('permission:orders.refund');
        Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])
            ->name('orders.invoice');
        Route::get('orders/export', [OrderController::class, 'export'])
            ->name('orders.export')
            ->middleware('permission:orders.export');
    });

    // ── PENGGUNA ──────────────────────────────────────────────────
    Route::middleware('permission:users.view')->group(function () {
        Route::resource('users', UserController::class)
            ->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::post('users/{user}/ban', [UserController::class, 'ban'])
            ->name('users.ban')
            ->middleware('permission:users.ban');
        Route::post('users/{user}/unban', [UserController::class, 'unban'])
            ->name('users.unban')
            ->middleware('permission:users.ban');
    });

    // ── FLASH SALE ────────────────────────────────────────────────
    Route::middleware('permission:flash_sales.view')->group(function () {
        Route::resource('flash-sales', FlashSaleController::class);
        Route::post('flash-sales/{flashSale}/toggle', [FlashSaleController::class, 'toggle'])
            ->name('flash-sales.toggle');
        Route::post('flash-sales/{flashSale}/items', [FlashSaleController::class, 'addItem'])
            ->name('flash-sales.items.store');
        Route::delete('flash-sales/{flashSale}/items/{item}', [FlashSaleController::class, 'removeItem'])
            ->name('flash-sales.items.destroy');
    });

    // ── KUPON ─────────────────────────────────────────────────────
    Route::middleware('permission:coupons.view')->group(function () {
        Route::resource('coupons', CouponController::class);
        Route::post('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])
            ->name('coupons.toggle');
    });

    // ── REVIEW ────────────────────────────────────────────────────
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])
        ->name('reviews.approve');
    Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])
        ->name('reviews.reject');
    Route::post('reviews/{review}/reply', [ReviewController::class, 'reply'])
        ->name('reviews.reply');

    // ── BANNER & KONTEN ───────────────────────────────────────────
    Route::resource('banners', BannerController::class);
    Route::post('banners/reorder', [BannerController::class, 'reorder'])
        ->name('banners.reorder');

    // ── SELLER & PENARIKAN ────────────────────────────────────────
    Route::middleware('permission:sellers.view')->group(function () {
        Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
        Route::get('sellers/{store}', [SellerController::class, 'show'])->name('sellers.show');
        Route::post('sellers/{store}/verify', [SellerController::class, 'verify'])
            ->name('sellers.verify')
            ->middleware('permission:sellers.approve');
        Route::post('sellers/{store}/suspend', [SellerController::class, 'suspend'])
            ->name('sellers.suspend')
            ->middleware('permission:sellers.suspend');

        Route::get('withdrawals', [WithdrawalController::class, 'index'])
            ->name('withdrawals.index');
        Route::post('withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])
            ->name('withdrawals.approve')
            ->middleware('permission:sellers.withdraw-approve');
        Route::post('withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'reject'])
            ->name('withdrawals.reject')
            ->middleware('permission:sellers.withdraw-approve');
    });

    // ── LAPORAN ───────────────────────────────────────────────────
    Route::middleware('permission:reports.view')->group(function () {
        Route::get('reports/revenue', [ReportController::class, 'revenue'])
            ->name('reports.revenue');
        Route::get('reports/products', [ReportController::class, 'products'])
            ->name('reports.products');
        Route::get('reports/export', [ReportController::class, 'export'])
            ->name('reports.export')
            ->middleware('permission:reports.export');
    });

    // ── PENGATURAN ────────────────────────────────────────────────
    Route::middleware('permission:settings.view')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])
            ->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])
            ->name('settings.update')
            ->middleware('permission:settings.update');
    });

    // ── AUDIT LOGS ────────────────────────────────────────────────
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs/{log}', [AuditLogController::class, 'show'])->name('audit-logs.show');

    // ── FAILED SYNC LOGS ──────────────────────────────────────────
    Route::get('failed-sync-logs', [FailedSyncLogController::class, 'index'])->name('failed-sync-logs.index');
    Route::post('failed-sync-logs/{log}/retry', [FailedSyncLogController::class, 'retry'])->name('failed-sync-logs.retry');

    // ── DISKUSI PRODUK ───────────────────────────────────────────
    Route::get('discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('discussions/{discussion}/reply', [DiscussionController::class, 'reply'])->name('discussions.reply');
    Route::delete('discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');
});
