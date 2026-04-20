<?php

namespace App\Http\Controllers\SchedulePeriods;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    
    public function __invoke(SchedulePeriod $schedulePeriod): JsonResponse
    {
        return response()->json($schedulePeriod);
    }
}
