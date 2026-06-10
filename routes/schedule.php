<?php

use App\Http\Controllers\Schedule\{
    BulkUpdateController,
    GenerationController,
    IndexController,
    LatestShowController,
    MyIndexController,
    MyShowController,
    ShowController,
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('schedules')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-schedule');
    Route::get('/latest', LatestShowController::class)->middleware('can:read-schedule');
    Route::prefix('my-schedules')->group(function () { // My Schedules
        Route::get('/', MyIndexController::class)->middleware('can:read-schedule');
        Route::get('/{period}', MyShowController::class)->middleware('can:show-schedule');
    });
    Route::post('/{period}/generate', [GenerationController::class, 'generate'])->middleware('can:generate-schedule');
    Route::get('/{period}', ShowController::class)->middleware('can:show-schedule');
    Route::put('/{period}/assignments', BulkUpdateController::class)->middleware('can:update-schedule');

});
