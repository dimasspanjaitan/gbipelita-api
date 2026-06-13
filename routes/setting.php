<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/settings', [SettingController::class, 'get']);
Route::middleware('auth:sanctum')->prefix('settings')->group(function () {
    Route::put('/', [SettingController::class, 'update'])->middleware('can:update-setting');
});
