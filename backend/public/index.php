<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Normalize REQUEST_URI if Apache rewrote with /public/ prefix on shared hosting
if (isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = preg_replace('#^/public/#i', '/', $_SERVER['REQUEST_URI']);
}
if (isset($_SERVER['UNENCODED_URL'])) {
    $_SERVER['UNENCODED_URL'] = preg_replace('#^/public/#i', '/', $_SERVER['UNENCODED_URL']);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
