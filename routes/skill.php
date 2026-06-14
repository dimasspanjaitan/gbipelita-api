<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Skill\{
    IndexController,
    ShowController,
    StoreController,
    UpdateController,
    DestroyController,
    RestoreController,
    ForceDeleteController
};

Route::middleware('auth:sanctum')->prefix('skills')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-user');
    Route::get('/{skill}', ShowController::class)->middleware('can:update-skill');
    Route::get('/{skill}/detail', ShowController::class)->middleware('can:show-skill');
    Route::post('/', StoreController::class)->middleware('can:create-skill');
    Route::put('/{skill}', UpdateController::class)->middleware('can:update-skill');
    Route::patch('/{skill}', UpdateController::class)->middleware('can:update-skill');
    Route::delete('/{skill}', DestroyController::class)->middleware('can:delete-skill');
    Route::post('/{skill}/restore', RestoreController::class)->middleware('can:restore-skill');
    Route::delete('/{skill}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-skill');
});
