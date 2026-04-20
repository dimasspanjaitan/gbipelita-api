<?php

use App\Http\Controllers\Schedule\{
    GenerationController,
};
use App\Http\Controllers\SchedulePeriods\{
    DestroyController,
    ForceDeleteController,
    IndexController,
    RestoreController,
    ShowController,
    StoreController,
    UpdateController
};
use Illuminate\Support\Facades\Route;

// SCHEDULE
Route::middleware('auth:sanctum')->prefix('schedules')->group(function () {
    Route::post(
        '/{period}/generate',
        [GenerationController::class, 'generate']
    );
});

// SCHEDULE PERIODS
Route::middleware('auth:sanctum')->prefix('schedule-periods')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-schedule-period');
    Route::get('/{schedulePeriod}', ShowController::class)->middleware('can:show-schedule-period');
    Route::post('/', StoreController::class)->middleware('can:create-schedule-period');
    Route::put('/{schedulePeriod}', UpdateController::class)->middleware('can:update-schedule-period');
    Route::patch('/{schedulePeriod}', UpdateController::class)->middleware('can:update-schedule-period');
    Route::delete('/{schedulePeriod}', DestroyController::class)->middleware('can:delete-schedule-period');
    Route::post('/{schedulePeriod}/restore', RestoreController::class)->middleware('can:restore-schedule-period');
    Route::delete('/{schedulePeriod}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-schedule-period');
});