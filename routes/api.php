<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Tambahkan middleware Sanctum agar SPA bisa pakai cookie
Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'web'
])->group(function () {
    Route::get('/sanctum/csrf-cookie', function () {
        return response()->json(['csrf_cookie' => 'set']);
    });
});

// Route::post('/users', [UserController::class, 'store']);
// Route::get('/users', [UserController::class, 'index']);

Route::get('/user/{id}', function (Request $request) {
    return $request->user();
});

Route::prefix('api')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('users', UserController::class);
    Route::get('users/{user}/posts', [PostController::class, 'getUserPosts']);
});
