<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function get(): JsonResponse
    {
        return response()->json(Setting::query()->sole());
    }
}
