<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Import the URL facade at the top
use Illuminate\Support\Facades\URL;
// Import the App facade
use Illuminate\Support\Facades\App;
// Import Paginator for Bootstrap styling
use Illuminate\Pagination\Paginator;
// Import Mail facade for SendGrid
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\MailManager;
use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;
// Import Observer
use App\Models\CourseEquivalency;
use App\Observers\CourseEquivalencyObserver;

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

        // Use Bootstrap 5 for pagination styling (fixes giant arrow issue)
        Paginator::useBootstrapFive();

        // Register SendGrid transport
        Mail::extend('sendgrid', function () {
            return new \App\Mail\Transport\SendGridTransport(
                new SendGrid(config('services.sendgrid.api_key'))
            );
        });

        // Register observers for automatic statistics synchronization
        CourseEquivalency::observe(CourseEquivalencyObserver::class);
    }
}
