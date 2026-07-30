<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists('/tmp')) {
            $this->app->useStoragePath('/tmp/storage');
            $this->app->instance('path.storage', '/tmp/storage');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (file_exists('/tmp')) {
            config([
                'logging.default' => 'stderr',
                'logging.channels.single.path' => '/tmp/storage/logs/laravel.log',
                'logging.channels.daily.path' => '/tmp/storage/logs/laravel.log',
                'logging.channels.emergency.path' => '/tmp/storage/logs/laravel.log',
                'view.compiled' => '/tmp/storage/framework/views',
            ]);
        }
    }
}
