<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolePermission\SyncController as PermissionSync;
use App\Http\Controllers\Role\{
    IndexController as RoleIndex,
    StoreController as RoleStore,
    UpdateController as RoleUpdate,
    DestroyController as RoleDestroy,
};

Route::middleware('auth:sanctum')->prefix('master')->group(function () {
    Route::get('/roles', RoleIndex::class);
    Route::post('/roles', RoleStore::class);
    Route::put('/roles/{role}', RoleUpdate::class);
    Route::delete('/roles/{role}', RoleDestroy::class);
    
    Route::post('/roles/{role}/sync-permissions', PermissionSync::class);
});