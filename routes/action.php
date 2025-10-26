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

Route::middleware(['auth:sanctum', 'role:developer'])
    ->prefix('actions')
    ->group(function () {
        Route::get('/', IndexController::class)->middleware('can:view-action');
        Route::get('/{action}', ShowController::class)->middleware('can:view-action');
        Route::post('/', StoreController::class)->middleware('can:create-action');
        Route::put('/{action}', UpdateController::class)->middleware('can:update-action');
        Route::patch('/{action}', UpdateController::class)->middleware('can:update-action');
        Route::delete('/{action}', DestroyController::class)->middleware('can:delete-action');
        Route::post('/{id}/restore', RestoreController::class)->middleware('can:restore-action');
        Route::delete('/{id}/force', ForceDeleteController::class)->middleware('can:force-delete-action');
    });
