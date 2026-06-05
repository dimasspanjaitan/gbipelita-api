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

        $availableSessionIds = ScheduleAvailability::query()
            ->where('schedule_period_id', $latestPeriodOpen->id)
            ->where('user_id', $userId)
            ->pluck('service_session_id')
            ->toArray();

        $submitted = ScheduleUserPeriodStatus::query()
            ->where('schedule_period_id', $latestPeriodOpen->id)
            ->where('user_id', $userId)
            ->value('has_submitted') ?? false;


        // transform ke format frontend
        $sessions = $latestPeriodOpen->sessions->map(function ($session) use ($availableSessionIds) {
            $session->is_available = in_array($session->id, $availableSessionIds);
            return $session;
        });

        // dd([$availableSessionIds, $sessions]);
        return response()->json([
            'period' => $latestPeriodOpen->unsetRelation('sessions') ?? null,
            'submitted' => $submitted,
            'sessions' => $sessions,
        ]);
    }
}
