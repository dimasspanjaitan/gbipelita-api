<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserPosition\{
    IndexController,
    ShowController,
    StoreController,
    UpdateController,
    DestroyController,
    RestoreController,
    ForceDeleteController
};

Route::middleware('auth:sanctum')->prefix('user-positions')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-user-position');
    Route::get('/{userPosition}', ShowController::class)->middleware('can:update-user-position');
    Route::get('/{userPosition}/detail', ShowController::class)->middleware('can:show-user-position');
    Route::post('/', StoreController::class)->middleware('can:create-user-position');
    Route::put('/{userPosition}', UpdateController::class)->middleware('can:update-user-position');
    Route::patch('/{userPosition}', UpdateController::class)->middleware('can:update-user-position');
    Route::delete('/{userPosition}', DestroyController::class)->middleware('can:delete-user-position');
    Route::post('/{userPosition}/restore', RestoreController::class)->middleware('can:restore-user-position');
    Route::delete('/{userPosition}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-user-position');
});
