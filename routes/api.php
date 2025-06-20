<?php

use App\Http\Controllers\Auth\AuthClientController;
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserController;


Route::prefix('client')->group(function () {
    Route::post('login', [AuthClientController::class, 'login']);
    Route::post('register', [AuthClientController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthClientController::class, 'me']);
        Route::post('logout', [AuthClientController::class, 'logout']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('products', ProductController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::apiResource('favorites', FavoriteController::class)
            ->only(['index', 'store', 'destroy']);
    });

});

Route::get('test-swagger', [\App\Http\Controllers\TestSwaggerController::class, 'test']);

Route::prefix('user')->group(function () {
    Route::post('login', [AuthUserController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthUserController::class, 'me']);
        Route::post('logout', [AuthUserController::class, 'logout']);
    });
});
