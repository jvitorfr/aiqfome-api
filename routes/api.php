<?php

use App\Http\Controllers\Admin\AuthUserController;
use App\Http\Controllers\Admin\ClientFavoritesController;
use App\Http\Controllers\Client\AuthClientController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);

Route::prefix('client')->group(function () {
    Route::post('login', [AuthClientController::class, 'login']);
    Route::post('register', [AuthClientController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthClientController::class, 'me']);
        Route::post('logout', [AuthClientController::class, 'logout']);



        Route::get('favorites', [FavoriteController::class, 'index']);
        Route::post('favorites/{product}', [FavoriteController::class, 'store']);
        Route::delete('favorites/{product}', [FavoriteController::class, 'destroy']);
    });
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthUserController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthUserController::class, 'me']);
        Route::post('logout', [AuthUserController::class, 'logout']);

        Route::apiResource('clients', ClientController::class);

        Route::prefix('clients/{client}')->group(function () {
            Route::get('favorites', [ClientFavoritesController::class, 'index']);
            Route::post('favorites/{product}', [ClientFavoritesController::class, 'store']);
            Route::delete('favorites/{product}', [ClientFavoritesController::class, 'destroy']);
        });
    });
});
