<?php

use App\Http\Controllers\Schedule\{
    BulkUpdateController,
    GenerationController,
    IndexController,
    ShowController,
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('schedules')->group(function () {
    Route::post(
        '/{period}/generate',
        [GenerationController::class, 'generate']
    );
    Route::get('/', IndexController::class);
    Route::get('/{period}', ShowController::class);
    Route::put('/{period}/assignments', BulkUpdateController::class);
});
