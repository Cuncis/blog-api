<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::post('/tokens/read-only', [AuthController::class, 'issueReadOnlyToken']);
    Route::get('/tokens', [AuthController::class, 'listTokens']);
    Route::delete('/tokens/{tokenId}', [AuthController::class, 'revokeToken']);

    // Reading posts only requires the 'posts:read' ability
    Route::middleware('abilities:posts.read')->group(function () {
        Route::get('/posts', [PostController::class, 'index']);
        Route::get('/posts/{post}', [PostController::class, 'show']);
        Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
        Route::get('/comments/{comment}', [CommentController::class, 'show']);
    });

    // Writing requires 'posts:write' — a read-only token gets blocked here
    Route::middleware(['abilities:posts:write', 'throttle:writes'])->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::patch('/posts/{post}', [PostController::class, 'update']);
        Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
        Route::put('/comments/{comment}', [CommentController::class, 'update']);
        Route::patch('/comments/{comment}', [CommentController::class, 'update']);
    });

    // Deleting requires the most sensitive ability, kept separate on purpose
    Route::middleware(['abilities:posts:delete', 'throttle:writes'])->group(function () {
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    });
});
