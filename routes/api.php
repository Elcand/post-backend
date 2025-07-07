<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('users', UserController::class);
    Route::get('users/{user}/posts', [PostController::class, 'getUserPosts']);
});
