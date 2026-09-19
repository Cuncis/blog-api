<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'Test route works!']);
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'time' => now()->toIso8601String(),
    ]);
});

Route::prefix('v1')->group(base_path('routes/api_v1.php'));
