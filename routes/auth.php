<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UpdateController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->middleware('can:read-profile');
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/check', [AuthController::class, '__invoke']);
        Route::put('/me/{user}', UpdateController::class)->middleware('can:update-profile');
    });
});