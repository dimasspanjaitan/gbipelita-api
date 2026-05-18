<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Illuminate\Support\Facades\DB;

class PublishController extends Controller
{
    public function __invoke(SchedulePeriod $period)
    {
        if ($period->status != 'generated') {
            return response()->json([
                'success' => false,
                'message' => "Only generated can be published"
            ], 422);
        }

        $totalVolunteers = DB::table("users")
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'volunteer')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->where('status', 'active')
            ->count();

        $submittedAvailabilityCount = DB::table('schedule_availabilities')
            ->where('schedule_period_id', $period->id)
            ->distinct('user_id')
            ->count('user_id');

        $notSubmittedAvailabilityCount = $totalVolunteers - $submittedAvailabilityCount;

        $period->update([
            'status' => 'published',
            'submitted_count' => $submittedAvailabilityCount,
            'not_submitted_count' => $notSubmittedAvailabilityCount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule period published successfully',
            'data' => $period,
        ]);
    }
}
