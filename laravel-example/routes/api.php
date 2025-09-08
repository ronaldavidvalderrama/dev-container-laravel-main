<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn() => ['ok' => true])->withoutMiddleware(['auth:api', 'scopes:posts.read']);


Route::prefix('posts')->group(function () {
    // 'scopes:posts.read'
    Route::middleware(['throttle:api', 'auth:api', 'role:viewer,editor,admin'])->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('{posts}', [PostController::class, 'show']);
    });

    //Escritor o administrador
    Route::middleware(['throttle:api', 'auth:api', 'role:editor,admin'])->group(function () {
        Route::post('/', [PostController::class, 'store'])->middleware('scopes:posts.write');
        Route::put('{post}', [PostController::class, 'update'])->middleware('scopes:posts.write');
        Route::delete('{post}', [PostController::class, 'destroy']);
        Route::post('{post}/restore', [PostController::class, 'restore'])
            ->middleware('scopes:post.write');
    });
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});