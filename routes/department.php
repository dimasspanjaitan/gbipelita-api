<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Department\{
    IndexController,
    ShowController,
    StoreController,
    UpdateController,
    DestroyController,
    RestoreController,
    ForceDeleteController
};

Route::middleware('auth:sanctum')->prefix('departments')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-department');
    Route::get('/{department}', ShowController::class)->middleware('can:update-department');
    Route::get('/{department}/detail', ShowController::class)->middleware('can:show-department');
    Route::post('/', StoreController::class)->middleware('can:create-department');
    Route::put('/{department}', UpdateController::class)->middleware('can:update-department');
    Route::patch('/{department}', UpdateController::class)->middleware('can:update-department');
    Route::delete('/{department}', DestroyController::class)->middleware('can:delete-department');
    Route::post('/{department}/restore', RestoreController::class)->middleware('can:restore-department');
    Route::delete('/{department}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-department');
});
