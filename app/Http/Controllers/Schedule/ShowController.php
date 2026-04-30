<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowController extends Controller
{
    public function __invoke(SchedulePeriod $period): JsonResponse
    {
        $scheduleAssignments = $period->assignments()->with(['session', 'user', 'requirement.skill.division'])->get();

        return response()->json([
            'period' => $period,
            'assignment' => $scheduleAssignments
        ]);
    }
}
