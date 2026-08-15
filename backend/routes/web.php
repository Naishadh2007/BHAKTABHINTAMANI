<?php

use Illuminate\Support\Facades\Route;

// Serve React SPA index.html for all frontend web routes
Route::get('/{any?}', function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    return response()->json([
        'message' => 'ReadVerse Backend API Server is running',
        'status'  => 'active',
    ]);
})->where('any', '^(?!(api|public/api)).*$');


