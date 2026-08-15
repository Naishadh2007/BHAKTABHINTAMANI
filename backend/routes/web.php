<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'ReadVerse Backend API Server is running locally',
        'status'  => 'active',
    ]);
});


