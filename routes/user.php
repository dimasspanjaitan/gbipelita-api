<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\{
    UserIndexController,
    UserShowController,
    UserStoreController,
    UserUpdateController,
    UserDestroyController,
    UserRestoreController,
    UserForceDeleteController
};

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/', UserIndexController::class);
    Route::get('/{user}', UserShowController::class);
    Route::post('/', UserStoreController::class);
    Route::put('/{user}', UserUpdateController::class);
    Route::patch('/{user}', UserUpdateController::class);
    Route::delete('/{user}', UserDestroyController::class);
    Route::post('/{id}/restore', UserRestoreController::class);
    Route::delete('/{id}/force', UserForceDeleteController::class);
});
