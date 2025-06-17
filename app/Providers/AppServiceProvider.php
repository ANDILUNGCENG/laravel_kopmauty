<?php

namespace App\Providers;

use App\Models\Produk;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            $storeId = 1; // Set store ID langsung ke 1
            $lowStockProducts = Produk::getLowStockProducts([$storeId]);
            $view->with('lowStockProducts', $lowStockProducts);
        });
    }
}
