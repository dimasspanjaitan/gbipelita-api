<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/settings', [SettingController::class, 'get']);
