<?php

use App\Http\Controllers\Schedule\{
    GenerationController,
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('schedules')->group(function () {
    Route::post(
        '/{period}/generate',
        [GenerationController::class, 'generate']
    );
});