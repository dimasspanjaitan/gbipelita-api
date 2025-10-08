<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\{
    IndexController,
    ShowController,
    StoreController,
    UpdateController,
    DestroyController,
    RestoreController,
    ForceDeleteController
};

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/', IndexController::class);
    Route::get('/{user}', ShowController::class);
    Route::post('/', StoreController::class);
    Route::put('/{user}', UpdateController::class);
    Route::patch('/{user}', UpdateController::class);
    Route::delete('/{user}', DestroyController::class);
    Route::post('/{id}/restore', RestoreController::class);
    Route::delete('/{id}/force', ForceDeleteController::class);
});
