<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Force /tmp/storage and stderr logging for serverless/Linux environments BEFORE Application configuration
if (file_exists('/tmp')) {
    putenv('APP_STORAGE=/tmp/storage');
    $_ENV['APP_STORAGE'] = '/tmp/storage';
    $_SERVER['APP_STORAGE'] = '/tmp/storage';

    putenv('LOG_CHANNEL=stderr');
    $_ENV['LOG_CHANNEL'] = 'stderr';
    $_SERVER['LOG_CHANNEL'] = 'stderr';
}

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

if (file_exists('/tmp')) {
    $app->useStoragePath('/tmp/storage');
    $app->instance('path.storage', '/tmp/storage');
}

return $app;