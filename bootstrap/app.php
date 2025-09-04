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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'guard.session' => App\Http\Middleware\GuardSession::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            $path = ltrim($request->path(), '/');
            return str_starts_with($path, 'admin') ? route('admin.login') : route('login');
        });

        // Fix 419 on admin login by exempting CSRF for the admin login POST
        $middleware->validateCsrfTokens(except: [
            'admin/login',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
