<?php

namespace App\Http\Controllers\ScheduleAvailability;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $schedulePeriodOpen = SchedulePeriod::where('status', 'open')->get();

        return response()->json($schedulePeriodOpen->load('department'));
    }
}
