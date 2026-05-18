<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\IndexController;

Route::middleware(['auth:sanctum'])
    ->prefix('dashboard')
    ->group(function () {
        Route::get('/', IndexController::class)->middleware('can:read-dashboard');
    });
