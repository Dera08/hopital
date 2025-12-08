<?php

// ============ bootstrap/app.php (Laravel 11) ============

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
        // Middleware global

        // Middleware pour groupes
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'active_user' => \App\Http\Middleware\EnsureUserIsActive::class,
            'mfa' => \App\Http\Middleware\VerifyMfa::class,
            'log_access' => \App\Http\Middleware\LogSensitiveAccess::class,
            'check_service' => \App\Http\Middleware\CheckServiceAccess::class,
        ]);

        // Middleware web par défaut
        $middleware->web(append: [
            \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// ============ app/Providers/AppServiceProvider.php ============

 