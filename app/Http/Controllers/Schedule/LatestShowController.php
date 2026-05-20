<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class LatestShowController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $period = SchedulePeriod::query()
            ->where('status', 'published')
            ->latest('created_at')
            ->first();

        $scheduleAssignments = $period->assignments()->with(['session', 'user', 'requirement.skill.division'])->get();

        return response()->json([
            'period' => $period,
            'assignments' => $scheduleAssignments
        ]);
    }
}
