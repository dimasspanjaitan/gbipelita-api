<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\{
    DestroyController,
    IndexController,
    StoreController,
    UpdateController,
};

Route::middleware(['auth:sanctum', 'role:developer|admin'])
    ->prefix('roles')
    ->group(function () {
        Route::get('/', IndexController::class)->middleware('can:view-roles');
        Route::post('/', StoreController::class)->middleware('can:create-roles');
        Route::put('/{id}', UpdateController::class)->middleware('can:update-roles');
        Route::delete('/{role}', DestroyController::class)->middleware('can:delete-roles');
    });
