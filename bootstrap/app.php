<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Mengecualikan webhook Midtrans dari CSRF
        $middleware->preventRequestForgery(except: [
            'midtrans/callback',
        ]);

        // Alias middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();

// Automatically use /tmp/storage for Linux/Vercel serverless environment
if (file_exists('/tmp')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;