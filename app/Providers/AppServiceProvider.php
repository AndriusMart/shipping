<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ShipmentDiscountCalculator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ShipmentDiscountCalculator::class, function () {
            return new ShipmentDiscountCalculator();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
