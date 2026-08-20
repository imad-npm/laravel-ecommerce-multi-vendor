<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL; // <-- add this

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       // $this->app->bind();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
           if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
