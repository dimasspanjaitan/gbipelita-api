<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Department\{
    IndexController as DepartmentIndex,
    ShowController as DepartmentShow,
    StoreController as DepartmentStore,
    UpdateController as DepartmentUpdate,
    DestroyController as DepartmentDestroy,
    RestoreController as DepartmentRestore,
    ForceDeleteController as DepartmentForceDelete
};

Route::middleware('auth:sanctum')->prefix('departments')->group(function () {
    Route::get('/', DepartmentIndex::class)->middleware('can:master_departments_view');
    Route::get('/{department}', DepartmentShow::class);
    Route::post('/', DepartmentStore::class);
    Route::put('/{department}', DepartmentUpdate::class);
    Route::patch('/{department}', DepartmentUpdate::class);
    Route::delete('/{department}', DepartmentDestroy::class);
    Route::post('/{department}/restore', DepartmentRestore::class);
    Route::delete('/{department}/force', DepartmentForceDelete::class);
});
