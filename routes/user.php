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
    Route::get('/', IndexController::class)->middleware('can:read-user');
    Route::get('/{user}', ShowController::class)->middleware('can:update-user');
    Route::get('/{user}/detail', ShowController::class)->middleware('can:show-user');
    Route::post('/', StoreController::class)->middleware('can:create-user');
    Route::put('/{user}', UpdateController::class)->middleware('can:update-user');
    Route::patch('/{user}', UpdateController::class)->middleware('can:update-user');
    Route::delete('/{user}', DestroyController::class)->middleware('can:delete-user');
    Route::post('/{user}/restore', RestoreController::class)->middleware('can:restore-user');
    Route::delete('/{user}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-user');
});
