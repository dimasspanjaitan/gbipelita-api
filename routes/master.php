<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolePermission\SyncController as PermissionSync;
use App\Http\Controllers\Role\{
    IndexController,
    StoreController,
    UpdateController,
    DestroyController,
};

Route::middleware('auth:sanctum')->prefix('master')->group(function () {
    Route::get('/roles', IndexController::class)->middleware('can:view-roles');
    Route::post('/roles', StoreController::class)->middleware('can:create-roles');
    Route::put('/roles/{role}', UpdateController::class)->middleware('can:update-roles');
    Route::delete('/roles/{role}', DestroyController::class)->middleware('can:delete-roles');
    
    Route::post('/roles/{role}/sync-permissions', PermissionSync::class);
});