<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAvailability;
use App\Models\SchedulePeriod;
use App\Models\ScheduleUserPeriodStatus;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{

    public function __invoke(SchedulePeriod $period): JsonResponse
    {
        $submittedUsers = User::query()
            ->whereHas('schedulePeriodStatuses', function ($q) use ($period) {
                $q->where('schedule_period_id', $period->id)
                    ->where('has_submitted', true);
            })
            ->get();

        $notSubmittedUsers = User::query()
            ->whereHas('schedulePeriodStatuses', function ($q) use ($period) {
                $q->where('schedule_period_id', $period->id)
                    ->where('has_submitted', false);
            })
            ->get();

        $scheduleAssignments = [];
        if ($period->status == "generated" || $period->status == "published") {
            $scheduleAssignments = $period->assignments()->with(['session', 'user', 'requirement.skill.division'])->get();
        }

        return response()->json([
            'schedule_period' => $period->load('department'),
            'service_sessions' => $period->sessions,
            'submitted_users' => $submittedUsers->load('availabilities.session'),
            'not_submitted_users' => $notSubmittedUsers,
            'assignments' => $scheduleAssignments
        ]);
    }
}
