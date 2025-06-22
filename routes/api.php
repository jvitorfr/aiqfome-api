<?php

use App\Http\Controllers\Admin\AuthUserController;
use App\Http\Controllers\Admin\ClientFavoritesController;
use App\Http\Controllers\Client\AuthClientController;
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->group(function () {
    Route::post('login', [AuthClientController::class, 'login']);
    Route::post('register', [AuthClientController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthClientController::class, 'me']);
        Route::post('logout', [AuthClientController::class, 'logout']);

        Route::apiResource('products', ProductController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::get('favorites', [FavoriteController::class, 'index']);
        Route::post('favorites/plus', [FavoriteController::class, 'plus']);
        Route::post('favorites/minus', [FavoriteController::class, 'minus']);
    });
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthUserController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthUserController::class, 'me']);
        Route::post('logout', [AuthUserController::class, 'logout']);

        Route::prefix('clients/{client}')->group(function () {
            Route::get('favorites', [ClientFavoritesController::class, 'index']);
            Route::post('favorites', [ClientFavoritesController::class, 'store']);
            Route::delete('favorites/{product}', [ClientFavoritesController::class, 'destroy']);

        });
    });
});
