<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Module\{
    DestroyController,
    ForceDeleteController,
    IndexController,
    RestoreController,
    ShowController,
    StoreController,
    UpdateController,
};

Route::middleware(['auth:sanctum', 'role:Developer'])
    ->prefix('modules')
    ->group(function () {
        Route::get('/', IndexController::class)->middleware('can:read-module');
        Route::get('/{module}', ShowController::class)->middleware('can:show-module');
        Route::post('/', StoreController::class)->middleware('can:create-module');
        Route::put('/{module}', UpdateController::class)->middleware('can:update-module');
        Route::patch('/{module}', UpdateController::class)->middleware('can:update-module');
        Route::delete('/{module}', DestroyController::class)->middleware('can:delete-module');
        Route::post('/{module}/restore', RestoreController::class)->middleware('can:restore-module');
        Route::delete('/{module}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-module');
    });
