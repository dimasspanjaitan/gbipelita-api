<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Volunteer\{
    IndexController,
    ShowController,
    StoreController,
    UpdateController,
    DestroyController,
    RestoreController,
    ForceDeleteController
};

Route::middleware('auth:sanctum')->prefix('volunteers')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-volunteer');
    Route::get('/{user}', ShowController::class)->middleware('can:show-volunteer');
    Route::post('/', StoreController::class)->middleware('can:create-volunteer');
    Route::put('/{user}', UpdateController::class)->middleware('can:update-volunteer');
    Route::patch('/{user}', UpdateController::class)->middleware('can:update-volunteer');
    Route::delete('/{user}', DestroyController::class)->middleware('can:delete-volunteer');
    Route::post('/{user}/restore', RestoreController::class)->middleware('can:restore-volunteer');
    Route::delete('/{user}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-volunteer');
});
