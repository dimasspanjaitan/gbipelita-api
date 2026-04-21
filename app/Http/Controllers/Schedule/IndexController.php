<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAssignment;
use App\Models\SchedulePeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $schedulePeriod = SchedulePeriod::where('status', 'generated')->first();
        $scheduleAssignment = ScheduleAssignment::where('schedule_period_id', $schedulePeriod->id)->get();

        return response()->json($scheduleAssignment->load('period', 'session', 'requirement.skill', 'user'));
    }
}
