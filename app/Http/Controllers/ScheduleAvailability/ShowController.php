<?php

namespace App\Http\Controllers\ScheduleAvailability;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAvailability;
use App\Models\SchedulePeriod;
use App\Models\ScheduleUserPeriodStatus;

class ShowController extends Controller
{
    public function __invoke(SchedulePeriod $period)
    {
        $userId = auth()->id();

        $availableSessionIds = ScheduleAvailability::query()
            ->where('schedule_period_id', $period->id)
            ->where('user_id', $userId)
            ->pluck('service_session_id')
            ->toArray();

        $scheduleUserPeriodStatus = ScheduleUserPeriodStatus::query()
            ->where('schedule_period_id', $period->id)
            ->where('user_id', $userId)
            ->first();

        $submitted = $scheduleUserPeriodStatus->has_submitted ?? false;

        // transform ke format frontend
        $sessions = $period->sessions->map(function ($session) use ($availableSessionIds) {
            $session->is_available = in_array($session->id, $availableSessionIds);
            return $session;
        });

        return response()->json([
            'period' => $period->load('department')->unsetRelation('sessions'),
            'sessions' => $sessions,
            'submitted' => $submitted,
            'notes' => $scheduleUserPeriodStatus->notes ?? null,
        ]);
    }
}
