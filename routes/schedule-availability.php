<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleAvailability\{
    IndexController,
    ShowController,
    StoreController
};

Route::middleware('auth:sanctum')->prefix('schedule-availabilities')->group(function () {
    Route::get('/', IndexController::class)->middleware('can:read-availability-schedule');
    Route::get('/{period}', ShowController::class)->middleware('can:show-availability-schedule');
    Route::post('/{period}', StoreController::class)->middleware('can:create-availability-schedule');
});
