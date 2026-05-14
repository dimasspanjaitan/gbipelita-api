<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAvailability;
use App\Models\SchedulePeriod;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{

    public function __invoke(SchedulePeriod $period): JsonResponse
    {
        $volunteers = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'volunteer');
            })
            ->get();

        $submittedUserIds = ScheduleAvailability::query()
            ->where('schedule_period_id', $period->id)
            ->pluck('user_id')
            ->unique();

        $submittedUsers = $volunteers->whereIn('id', $submittedUserIds)->values();
        $notSubmittedUsers = $volunteers->whereNotIn('id', $submittedUserIds)->values();

        $scheduleAssignments = [];
        if ($period->status == "generated" || $period->status == "published") {
            $scheduleAssignments = $period->assignments()->with(['session', 'user', 'requirement.skill.division'])->get();
        }

        return response()->json([
            'schedule_period' => $period->load('department'),
            'submitted_users' => $submittedUsers,
            'not_submitted_users' => $notSubmittedUsers,
            'assignments' => $scheduleAssignments
        ]);
    }
}
