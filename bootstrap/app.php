<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'admin' => \App\Http\Middleware\CheckAdmin::class,
            'require.2fa' => \App\Http\Middleware\Require2FASetup::class,
        ]);

        // Apply 2FA middleware to all web routes after authentication
        $middleware->web(append: [
            \App\Http\Middleware\Require2FASetup::class,
            \App\Http\Middleware\DetectSecurityThreats::class,
        ]);
    })
    ->withProviders([
        App\Providers\VisionServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();