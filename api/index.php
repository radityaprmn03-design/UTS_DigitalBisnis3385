<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// 1. Prepare writable storage directories in /tmp for Vercel Serverless environment
$storageDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Copy SQLite database template to /tmp if not already present
if (!file_exists('/tmp/database.sqlite')) {
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        copy(__DIR__ . '/../database/database.sqlite', '/tmp/database.sqlite');
    } else {
        touch('/tmp/database.sqlite');
    }
}

// 3. Force environment overrides for Serverless execution
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';
putenv('LOG_CHANNEL=stderr');

$_ENV['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_CONNECTION'] = 'sqlite';
putenv('DB_CONNECTION=sqlite');

$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
$_SERVER['DB_DATABASE'] = '/tmp/database.sqlite';
putenv('DB_DATABASE=/tmp/database.sqlite');

$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

// 4. Register Autoloader & Bootstrap Laravel Application
require __DIR__ . '/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Dynamically bind storage path to writable /tmp/storage directory
$app->useStoragePath('/tmp/storage');

// 5. Handle HTTP Request using Laravel Kernel
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
