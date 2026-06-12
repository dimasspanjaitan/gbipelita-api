<?php

use App\Http\Controllers\SchedulePeriod\{
    DestroyController,
    ForceDeleteController,
    IndexController,
    OpenController,
    PublishController,
    RestoreController,
    ShowController,
    ShowDetailController,
    StoreController,
    UpdateController
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('schedule-periods')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-schedule-period');
    Route::get('/{period}', ShowController::class)->middleware('can:show-schedule-period');
    Route::get('/{period}/detail', ShowDetailController::class)->middleware('can:show-schedule-period');
    Route::post('/', StoreController::class)->middleware('can:create-schedule-period');
    Route::put('/{period}', UpdateController::class)->middleware('can:update-schedule-period');
    Route::patch('/{period}', UpdateController::class)->middleware('can:update-schedule-period');
    Route::patch('/{period}/open', OpenController::class)->middleware('can:open-schedule-period');
    Route::patch('/{period}/publish', PublishController::class)->middleware('can:update-schedule-period');
    Route::delete('/{period}', DestroyController::class)->middleware('can:delete-schedule-period');
    Route::post('/{period}/restore', RestoreController::class)->middleware('can:restore-schedule-period');
    Route::delete('/{period}/force-delete', ForceDeleteController::class)->middleware('can:force-delete-schedule-period');
});