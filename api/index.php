<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set environment variables FIRST
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';
putenv('VERCEL=1');

// Change to project root directory
chdir(__DIR__ . '/..');

// Define LARAVEL_START if not defined
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Create necessary directories
$directories = [
    '/tmp/storage',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Catch any errors
try {
    // Register the Composer autoloader
    require __DIR__ . '/../vendor/autoload.php';
    
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Handle the request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "=== ERROR ===\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\n=== TRACE ===\n";
    echo $e->getTraceAsString();
    exit;
}
