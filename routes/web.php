<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\ContactController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/service', [ServiceController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});