<?php

namespace App\Http\Controllers\ScheduleAvailability;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAvailability;
use App\Models\SchedulePeriod;
use App\Models\ScheduleUserPeriodStatus;

class LatestShowController extends Controller
{
    public function __invoke()
    {
        $userId = auth()->id();

        $latestPeriodOpen = SchedulePeriod::query()
            ->with('department')
            ->where('status', 'open')
            ->latest('created_at')
            ->first();

        if (!$latestPeriodOpen) {
            return response()->json([
                'period' => null,
                'sessions' => [],
                'submitted' => false,
                'notes' => null,
            ]);
        }

        $availableSessionIds = ScheduleAvailability::query()
            ->where('schedule_period_id', $latestPeriodOpen->id)
            ->where('user_id', $userId)
            ->pluck('service_session_id')
            ->toArray();

        $scheduleUserPeriodStatus = ScheduleUserPeriodStatus::query()
            ->where('schedule_period_id', $latestPeriodOpen->id)
            ->where('user_id', $userId)
            ->first();

        $submitted = $scheduleUserPeriodStatus->has_submitted ?? false;

        // transform ke format frontend
        $sessions = $latestPeriodOpen->sessions->map(function ($session) use ($availableSessionIds) {
            $session->is_available = in_array($session->id, $availableSessionIds);
            return $session;
        });

        return response()->json([
            'period' => $latestPeriodOpen->unsetRelation('sessions') ?? null,
            'sessions' => $sessions,
            'submitted' => $submitted,
            'notes' => $scheduleUserPeriodStatus->notes ?? null,
        ]);
    }
}
