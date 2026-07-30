<?php

// 1. Prepare storage and bootstrap directories in /tmp for Vercel Serverless environment
$directories = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0777, true);
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

// 3. Script environment fixes for Vercel Serverless routing & HTTPS
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

// 4. Force environment overrides for Serverless execution
putenv('VERCEL=1');
putenv('APP_URL=https://uts-digital-bisnis3385-hngj-plum.vercel.app');
putenv('APP_STORAGE=/tmp/storage');
putenv('APP_KEY=base64:CZEnlThvyh/lnmIYamboeY5jOVJoylUQqJFGRDKNIiA=');
putenv('APP_ENV=production');
putenv('APP_DEBUG=true');
putenv('LOG_CHANNEL=stderr');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=/tmp/database.sqlite');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');
putenv('GOOGLE_REDIRECT_URI=https://uts-digital-bisnis3385-hngj-plum.vercel.app/auth/google/callback');

$_ENV['APP_URL'] = 'https://uts-digital-bisnis3385-hngj-plum.vercel.app';
$_SERVER['APP_URL'] = 'https://uts-digital-bisnis3385-hngj-plum.vercel.app';

$_ENV['APP_STORAGE'] = '/tmp/storage';
$_SERVER['APP_STORAGE'] = '/tmp/storage';

$_ENV['APP_KEY'] = 'base64:CZEnlThvyh/lnmIYamboeY5jOVJoylUQqJFGRDKNIiA=';
$_SERVER['APP_KEY'] = 'base64:CZEnlThvyh/lnmIYamboeY5jOVJoylUQqJFGRDKNIiA=';

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

$_ENV['GOOGLE_REDIRECT_URI'] = 'https://uts-digital-bisnis3385-hngj-plum.vercel.app/auth/google/callback';
$_SERVER['GOOGLE_REDIRECT_URI'] = 'https://uts-digital-bisnis3385-hngj-plum.vercel.app/auth/google/callback';

// 5. Forward Vercel request to Laravel public/index.php
require __DIR__ . '/../public/index.php';
