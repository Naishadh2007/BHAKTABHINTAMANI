<?php

use Illuminate\Support\Facades\Route;

// Serve React SPA index.html for all web routes
Route::get('/{any?}', function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return file_get_contents($indexPath);
    }
    return response()->json(['message' => 'Reading Web API is active']);
})->where('any', '^(?!api).*$');

