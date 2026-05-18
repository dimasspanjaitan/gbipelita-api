<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateScheduleJob;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GenerationController extends Controller
{
    public function generate(SchedulePeriod $period): JsonResponse
    {
        if ($period->status === 'generating') {
            return response()->json([
                'message' => 'Schedule is already generating.'
            ], 409);
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
            'status' => 'generating',
            'submitted_count' => $submittedAvailabilityCount,
            'not_submitted_count' => $notSubmittedAvailabilityCount
        ]);

        GenerateScheduleJob::dispatch($period->id);

        return response()->json([
            'message' => 'Schedule generated started.'
        ]);
    }
}
