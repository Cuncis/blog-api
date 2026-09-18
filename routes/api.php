<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json(['message' => 'Test route works!']);
});

Route::apiResource('posts', \App\Http\Controllers\PostController::class);

Route::apiResource('posts.comments', \App\Http\Controllers\CommentController::class);
