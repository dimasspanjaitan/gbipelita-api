<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\{
    DestroyController,
    IndexController,
    StoreController,
    UpdateController,
    ShowController,
    RestoreController,
    ForceDeleteController,
};

Route::middleware(['auth:sanctum', 'role:Developer'])
    ->prefix('roles')
    ->group(function () {
        Route::get('/', IndexController::class)->middleware('can:read-role');
        Route::get('/{role}', ShowController::class)->middleware('can:show-role');
        Route::post('/', StoreController::class)->middleware('can:create-role');
        Route::put('/{role}', UpdateController::class)->middleware('can:update-role');
        Route::delete('/{role}', DestroyController::class)->middleware('can:delete-role');
        Route::post('/{role}/restore', RestoreController::class)->middleware('can:restore-role');
        Route::delete('/{role}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-role');
    });
