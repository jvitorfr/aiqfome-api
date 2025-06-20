<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ClientController;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('clients', ClientController::class);
});
