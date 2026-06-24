<?php

namespace App\Http\Controllers\Schedule;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ScheduleAssignment;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;

class MyIndexController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = auth()->user();

        $periods = SchedulePeriod::query()
            ->where('status', 'published')
            ->paginate(
                request('limit', 10)
            );

        // Ambil semua assignment user
        $myAssignments = ScheduleAssignment::query()
            ->where('user_id', $user->id)
            ->get([
                'schedule_period_id',
                'service_session_id',
            ]);

        // Group session per period
        $sessionIdsByPeriod = $myAssignments
            ->groupBy('schedule_period_id')
            ->map(fn($items) => $items
                ->pluck('service_session_id')
                ->unique()
                ->values());

        // Ambil semua period yang sedang tampil
        $periodIds = $periods
            ->getCollection()
            ->pluck('id');

        // Ambil semua assignment yang mungkin dibutuhkan
        $allAssignments = ScheduleAssignment::query()
            ->whereIn('schedule_period_id', $periodIds)
            ->with([
                'session',
                'user',
                'requirement.skill.division',
            ])
            ->get()
            ->groupBy('schedule_period_id');

        $periods->getCollection()->transform(
            function ($period) use (
                $allAssignments,
                $sessionIdsByPeriod
            ) {
                $sessionIds = $sessionIdsByPeriod
                    ->get($period->id, collect());

                $assignments = $allAssignments
                    ->get($period->id, collect())
                    ->whereIn('service_session_id', $sessionIds)
                    ->values();

                $period->setAttribute(
                    'assignments',
                    $assignments
                );

                return $period;
            }
        );

        return response()->json(ApiResponse::paginate($periods));
    }
}
