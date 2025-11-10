<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Division\{
    IndexController,
    ShowController,
    StoreController,
    UpdateController,
    DestroyController,
    RestoreController,
    ForceDeleteController
};

Route::middleware('auth:sanctum')->prefix('divisions')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:view-division');
    Route::get('/{division}', ShowController::class)->middleware('can:view-division');
    Route::post('/', StoreController::class)->middleware('can:create-division');
    Route::put('/{division}', UpdateController::class)->middleware('can:update-division');
    Route::patch('/{division}', UpdateController::class)->middleware('can:update-division');
    Route::delete('/{division}', DestroyController::class)->middleware('can:delete-division');
    Route::post('/{division}/restore', RestoreController::class)->middleware('can:restore-division');
    Route::delete('/{division}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-division');
});
