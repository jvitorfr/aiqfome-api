<?php

use App\Http\Controllers\Auth\AuthClientController;
use Illuminate\Support\Facades\Route;


// JWT para clientes
Route::prefix('client')->group(function () {
    Route::post('login', [AuthClientController::class, 'login']);
    Route::get('me', [AuthClientController::class, 'me']);
    Route::post('register', [AuthClientController::class, 'register']);
    Route::post('logout', [AuthClientController::class, 'logout']);
});

// Sanctum para usuários/admins
Route::prefix('user')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [UserAuthController::class, 'me']);
        Route::post('logout', [UserAuthController::class, 'logout']);
    });
});
