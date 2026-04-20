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

        $period->load([
            'department',
            'sessions' => function ($q) {
                $q->orderBy('service_date')
                    ->orderBy('session_number');
            }
        ]);

        $availableSessionIds = ScheduleAvailability::query()
            ->where('schedule_period_id', $period->id)
            ->where('user_id', $userId)
            ->pluck('service_session_id')
            ->toArray();

        $submitted = ScheduleUserPeriodStatus::query()
            ->where('schedule_period_id', $period->id)
            ->where('user_id', $userId)
            ->value('has_submitted') ?? false;

        // transform ke format frontend
        $sessions = $period->sessions->map(function ($session) use ($availableSessionIds) {
            return [
                'id' => $session->id,
                'date' => $session->service_date,
                'week' => $session->week_number,
                'session_number' => $session->session_number,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
                'is_available' => in_array($session->id, $availableSessionIds),
            ];
        });

        return response()->json([
            'department' => $period->department,
            'period' => [
                'id' => $period->id,
                'month' => $period->month,
                'year' => $period->year,
                'status' => $period->status,
            ],
            'submitted' => $submitted,
            'sessions' => $sessions,
        ]);
    }
}
