<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAssignment;
use App\Models\SchedulePeriod;

class MyShowController extends Controller
{
    public function __invoke(SchedulePeriod $period)
    {
        $user = auth()->user();

        $mySessionIds = ScheduleAssignment::query()
            ->where('schedule_period_id', $period->id)
            ->where('user_id', $user->id)
            ->pluck('service_session_id')
            ->unique();

        $assignments = ScheduleAssignment::query()
            ->where('schedule_period_id', $period->id)
            ->whereIn('service_session_id', $mySessionIds)
            ->with([
                'session',
                'user',
                'requirement.skill.division'
            ])
            ->get();

        $period->assignments = $assignments;
        
        return response()->json($period);
    }
}
