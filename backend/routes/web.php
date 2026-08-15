<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChapterController;

// Explicit API routes in web.php to guarantee resolution on any shared hosting environment
Route::get('/api/chapters', [ChapterController::class, 'index']);
Route::get('/api/chapters/{id}', [ChapterController::class, 'show']);
Route::get('/public/api/chapters', [ChapterController::class, 'index']);
Route::get('/public/api/chapters/{id}', [ChapterController::class, 'show']);

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
})->where('any', '^(?!(api|public/api|test_db)).*$');


