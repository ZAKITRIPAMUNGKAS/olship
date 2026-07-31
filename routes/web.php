<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\SearchController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\CategoryController;
use App\Http\Controllers\Storefront\FlashSaleController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\PaymentController;
use App\Http\Controllers\Storefront\CustomerDashboardController;
use App\Http\Controllers\ShippingController;

// Shipping AJAX
Route::prefix('shipping')->name('shipping.')->group(function () {
    Route::get('/provinces', [ShippingController::class, 'provinces'])->name('provinces');
    Route::get('/cities/{provinceId}', [ShippingController::class, 'cities'])->name('cities');
    Route::post('/cost', [ShippingController::class, 'cost'])->name('cost');
});

// Storefront
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search-suggestions', [\App\Http\Controllers\Storefront\SearchSuggestionController::class, 'index'])->name('search.suggestions');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale');

// Halaman statis
Route::view('/syarat-ketentuan', 'storefront.static.terms')->name('terms');
Route::view('/kebijakan-privasi', 'storefront.static.privacy')->name('privacy');

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/{item}', [CartController::class, 'remove'])->name('remove');
});

// Auth required
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon.apply');
    Route::post('/checkout/coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');

    // Payment
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/{order}/finish', [PaymentController::class, 'finish'])->name('payment.finish');

        // Dashboard
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [CustomerDashboardController::class, 'index'])->name('index');
            Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
            Route::get('/orders/{orderNumber}', [CustomerDashboardController::class, 'orderDetail'])->name('orders.show');
            Route::get('/notifications', [CustomerDashboardController::class, 'notifications'])->name('notifications.index');
            
            // Addresses
            Route::resource('addresses', \App\Http\Controllers\Storefront\AddressController::class);
            Route::post('addresses/{address}/set-default', [\App\Http\Controllers\Storefront\AddressController::class, 'setDefault'])->name('addresses.set-default');

            // Wishlist
            Route::get('/wishlist', [\App\Http\Controllers\Storefront\WishlistController::class, 'index'])->name('wishlist.index');
            Route::post('/wishlist/toggle', [\App\Http\Controllers\Storefront\WishlistController::class, 'toggle'])->name('wishlist.toggle');
            Route::delete('/wishlist/{wishlist}', [\App\Http\Controllers\Storefront\WishlistController::class, 'destroy'])->name('wishlist.destroy');
            // Profile
            Route::get('/profile', [\App\Http\Controllers\Storefront\CustomerDashboardController::class, 'profile'])->name('profile');
            Route::put('/profile', [\App\Http\Controllers\Storefront\CustomerDashboardController::class, 'updateProfile'])->name('profile.update');

            // Reviews
            Route::post('/reviews', [\App\Http\Controllers\Storefront\ReviewController::class, 'store'])->name('reviews.store');

            // Discussions
            Route::post('/products/{product}/discussions', [\App\Http\Controllers\Storefront\DiscussionController::class, 'store'])->name('products.discussions.store');
        });
});

// Midtrans Webhook Callback (Public / CSRF Exempted)
Route::post('/payment/callback', [\App\Http\Controllers\Storefront\PaymentCallbackController::class, 'handle'])
    ->name('payment.callback');

require __DIR__.'/auth.php';
