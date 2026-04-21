<?php

use App\Http\Controllers\Schedule\{
    GenerationController,
    IndexController,
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('schedules')->group(function () {
    Route::post(
        '/{period}/generate',
        [GenerationController::class, 'generate']
    );
    Route::get('/', IndexController::class);
});