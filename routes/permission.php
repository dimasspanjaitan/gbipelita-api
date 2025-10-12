<?php

use App\Http\Controllers\Permission\IndexController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:developer'])
    ->prefix('permissions')
    ->group(function () {
        Route::get('/', IndexController::class);
    });
