<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Set Vercel environment flag
if (!getenv('VERCEL')) {
    putenv('VERCEL=1');
    $_ENV['VERCEL'] = '1';
}

// Set default environment variables if not set
if (!getenv('APP_ENV')) {
    putenv('APP_ENV=production');
    $_ENV['APP_ENV'] = 'production';
}

// Temporarily enable debug to see actual errors
if (!getenv('APP_DEBUG')) {
    putenv('APP_DEBUG=true');
    $_ENV['APP_DEBUG'] = 'true';
}

// Set session and cache drivers if not already set
// Default to file for serverless environments (Vercel) to avoid database dependency during bootstrap
if (!getenv('SESSION_DRIVER')) {
    putenv('SESSION_DRIVER=file');
    $_ENV['SESSION_DRIVER'] = 'file';
}

if (!getenv('CACHE_STORE')) {
    putenv('CACHE_STORE=file');
    $_ENV['CACHE_STORE'] = 'file';
}

// Ensure /tmp directories exist for serverless environment
$tmpPaths = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/private',
    '/tmp/storage/app/public',
];

foreach ($tmpPaths as $path) {
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
try {
    // Delete cached service provider files that might reference dev dependencies
    // Laravel will regenerate them without the missing providers
    $cacheFiles = [
        __DIR__.'/../bootstrap/cache/packages.php',
        __DIR__.'/../bootstrap/cache/services.php',
    ];
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }
    
    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    $app->handleRequest(Request::capture());
} catch (Throwable $e) {
    // Better error handling for Vercel
    http_response_code(500);
    
    // Log the error if possible
    if (function_exists('error_log')) {
        error_log('Laravel Error: ' . $e->getMessage());
        error_log('File: ' . $e->getFile() . ':' . $e->getLine());
        error_log('Stack trace: ' . $e->getTraceAsString());
    }
    
    // Return a simple error response with more details
    header('Content-Type: application/json');
    $debug = getenv('APP_DEBUG') === 'true' || getenv('APP_DEBUG') === '1';
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $debug ? $e->getMessage() : 'An error occurred. Please check your environment variables and database configuration.',
        'file' => $debug ? $e->getFile() : null,
        'line' => $debug ? $e->getLine() : null,
        'trace' => $debug ? $e->getTraceAsString() : null,
    ], JSON_PRETTY_PRINT);
    exit(1);
}

