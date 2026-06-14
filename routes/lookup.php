<?php

use App\Http\Controllers\Department\LookupController as DepartmentLookupController;
use App\Http\Controllers\Division\LookupController as DivisionLookupController;
use App\Http\Controllers\Role\LookupController as RoleLookupController;
use App\Http\Controllers\User\LookupController as UserLookupController;
use App\Http\Controllers\Volunteer\LookupController as VolunteerLookupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('lookups')
    ->group(function () {
        Route::get('/roles', RoleLookupController::class)->middleware('can:lookup-role');
        Route::get('/users', UserLookupController::class)->middleware('can:lookup-user');
        Route::get('/departments', DepartmentLookupController::class)->middleware('can:lookup-department');
        Route::get('/divisions', DivisionLookupController::class)->middleware('can:lookup-division');
        Route::get('/volunteers', VolunteerLookupController::class)->middleware('can:lookup-volunteer');
    });
