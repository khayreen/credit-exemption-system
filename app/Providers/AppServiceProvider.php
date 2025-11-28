<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Import the URL facade at the top
use Illuminate\Support\Facades\URL;
// Import the App facade
use Illuminate\Support\Facades\App;

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
        // This code forces Laravel to use the APP_URL from your .env file
        // when generating links in a local environment. This will fix the
        // "localhost" issue in your verification emails.

        // FIX: Use the App facade to check the environment
        if (App::isLocal()) {
            URL::forceRootUrl(config('app.url'));
        }
    }
}
