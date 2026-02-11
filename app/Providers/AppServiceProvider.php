<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Force HTTPS trong production hoặc khi có header từ proxy
        if ($this->app->environment('production') || request()->server->has('HTTP_X_FORWARDED_PROTO')) {
            URL::forceScheme('https');
        }
    }
}
