<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

try {
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

    // 3. Script environment fixes for Vercel Serverless routing
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

    // 4. Force environment overrides for Serverless execution
    if (empty(getenv('APP_KEY'))) {
        putenv('APP_KEY=base64:CZEnlThvyh/lnmIYamboeY5jOVJoylUQqJFGRDKNIiA=');
        $_ENV['APP_KEY'] = 'base64:CZEnlThvyh/lnmIYamboeY5jOVJoylUQqJFGRDKNIiA=';
        $_SERVER['APP_KEY'] = 'base64:CZEnlThvyh/lnmIYamboeY5jOVJoylUQqJFGRDKNIiA=';
    }

    putenv('APP_ENV=production');
    putenv('APP_DEBUG=true');
    putenv('LOG_CHANNEL=stderr');
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=/tmp/database.sqlite');
    putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
    putenv('SESSION_DRIVER=cookie');
    putenv('CACHE_STORE=array');

    $_ENV['LOG_CHANNEL'] = 'stderr';
    $_SERVER['LOG_CHANNEL'] = 'stderr';

    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_CONNECTION'] = 'sqlite';

    $_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
    $_SERVER['DB_DATABASE'] = '/tmp/database.sqlite';

    $_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
    $_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';

    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';

    // 5. Register Autoloader & Bootstrap Laravel Application
    require __DIR__ . '/../vendor/autoload.php';

    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Dynamically bind storage path to writable /tmp/storage directory
    $app->useStoragePath('/tmp/storage');

    // 6. Auto-migrate SQLite tables if not present on serverless container
    try {
        if (!Schema::hasTable('categories')) {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        }
    } catch (\Throwable $e) {
        error_log('Auto-migration note: ' . $e->getMessage());
    }

    // 7. Handle HTTP Request using Laravel Kernel
    $kernel = $app->make(Kernel::class);

    $response = $kernel->handle(
        $request = Request::capture()
    );

    $response->send();

    $kernel->terminate($request, $response);

} catch (\Throwable $fatalError) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "VERCEL DEPLOYMENT FATAL ERROR:\n";
    echo $fatalError->getMessage() . "\n\n";
    echo $fatalError->getTraceAsString();
}
