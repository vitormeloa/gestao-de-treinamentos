<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api([
            EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->web();
        $middleware->encryptCookies();
        $middleware->validateCsrfTokens(except: [
            'http://localhost:8080/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        });
    })->create();
