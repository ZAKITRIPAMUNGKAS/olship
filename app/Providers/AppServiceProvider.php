<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Policies\OrderPolicy;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Models\Withdrawal;
use App\Policies\WithdrawalPolicy;
use App\Models\Store;
use App\Policies\StorePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('rajaongkir', function () {
            return Http::withHeaders([
                'key' => config('rajaongkir.api_key'),
            ])->baseUrl(config('rajaongkir.base_url'))
              ->timeout(15)
              ->retry(2, 200);
        });

        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Withdrawal::class, WithdrawalPolicy::class);
        Gate::policy(Store::class, StorePolicy::class);

        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
