<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAssignment;
use App\Models\SchedulePeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(SchedulePeriod $period): JsonResponse
    {
        $scheduleAssignment = ScheduleAssignment::where('schedule_period_id', $period->id)->get();

        return response()->json([
            'period' => $period,
            'assignment' => $scheduleAssignment->load('session', 'requirement.skill', 'user')
        ]);
    }
}
