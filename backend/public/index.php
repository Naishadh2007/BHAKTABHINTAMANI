<?php

// Check if Composer autoloader exists
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    define('LARAVEL_START', microtime(true));

    if (isset($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = preg_replace('#^/public/#i', '/', $_SERVER['REQUEST_URI']);
    }

    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }

    require __DIR__.'/../vendor/autoload.php';

    $app = require_once __DIR__.'/../bootstrap/app.php';
    $app->handleRequest(Illuminate\Http\Request::capture());
} else {
    // Fall back to root index.php logic
    require __DIR__ . '/../index.php';
}
