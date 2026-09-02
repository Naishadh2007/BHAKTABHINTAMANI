<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChapterController;

// Explicit API routes in web.php to guarantee resolution on any shared hosting environment
Route::get('/api/chapters', [ChapterController::class, 'index']);
Route::get('/api/chapters/{id}', [ChapterController::class, 'show']);
Route::get('/public/api/chapters', [ChapterController::class, 'index']);
Route::get('/public/api/chapters/{id}', [ChapterController::class, 'show']);

// Serve React SPA index.html or redirect /admin routes to frontend dev server
Route::get('/{any?}', function ($any = null) {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    if (request()->is('admin*')) {
        return redirect('http://localhost:5173/admin/login');
    }
    return redirect('http://localhost:5173');
})->where('any', '^(?!(api|public/api|test_db)).*$');


