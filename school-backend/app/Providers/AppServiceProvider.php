<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // You can bind interfaces here if needed.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // You can place boot logic here — like view composers, schema settings, etc.
    }
}
