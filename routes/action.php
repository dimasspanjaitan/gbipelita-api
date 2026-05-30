<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Action\{
    DestroyController,
    ForceDeleteController,
    IndexController,
    RestoreController,
    ShowController,
    StoreController,
    UpdateController,
};

Route::middleware(['auth:sanctum'])
    ->prefix('actions')
    ->group(function () {
        Route::get('/', IndexController::class)->middleware('can:read-action');
        Route::get('/{action}', ShowController::class)->middleware('can:show-action');
        Route::post('/', StoreController::class)->middleware('can:create-action');
        Route::put('/{action}', UpdateController::class)->middleware('can:update-action');
        Route::patch('/{action}', UpdateController::class)->middleware('can:update-action');
        Route::delete('/{action}', DestroyController::class)->middleware('can:delete-action');
        Route::post('/{action}/restore', RestoreController::class)->middleware('can:restore-action');
        Route::delete('/{action}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-action');
    });
