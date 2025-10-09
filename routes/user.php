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
    Route::get('/', IndexController::class)->middleware('can:view-users');
    Route::get('/{user}', ShowController::class)->middleware('can:view-users');
    Route::post('/', StoreController::class)->middleware('can:create-users');
    Route::put('/{user}', UpdateController::class)->middleware('can:update-users');
    Route::patch('/{user}', UpdateController::class)->middleware('can:update-users');
    Route::delete('/{user}', DestroyController::class)->middleware('can:delete-users');
    Route::post('/{id}/restore', RestoreController::class)->middleware('can:restore-users');
    Route::delete('/{id}/force', ForceDeleteController::class)->middleware('can:force-delete-users');
});
