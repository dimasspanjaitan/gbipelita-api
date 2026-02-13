<?php

use App\Http\Controllers\Schedule\{
    GenerationController,
    PeriodController
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('schedule')->group(function () {
    Route::post(
        '/{period}/generate',
        [GenerationController::class, 'generate']
    );
    Route::post(
        '/{period}/assignments',
        [PeriodController::class, 'generate']
    );
});
