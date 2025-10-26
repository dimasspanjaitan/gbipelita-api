<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Module\IndexController;

Route::middleware(['auth:sanctum', 'role:developer'])
    ->prefix('modules')
    ->group(function () {
        Route::get('/', IndexController::class)->middleware('can:view-module');
    });
