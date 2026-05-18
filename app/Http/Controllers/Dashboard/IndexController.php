<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $volunteers = DB::table("users")
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'volunteer')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->count();
        $departments = DB::table('departments')
            ->count();
        $divisions = DB::table('divisions')
            ->count();
        $skills = DB::table('skills')
            ->count();
        $schedulePeriods = DB::table('schedule_periods')
            ->count();
        $activePeriod = SchedulePeriod::query()
            ->latest()
            ->first();

        $sessionsCount = 0;
        $assignmentsCount = 0;
        $submittedAvailability = 0;

        if($activePeriod) {
            $sessionsCount = DB::table('service_sessions')
                ->where('schedule_period_id', $activePeriod->id)
                ->count();
            $assignmentsCount = DB::table('schedule_assignments')
                ->where('schedule_period_id', $activePeriod->id)
                ->count();
            $submittedAvailability = DB::table('schedule_availabilities')
                ->where('schedule_period_id', $activePeriod->id)
                ->distinct('user_id')
                ->count('user_id');
        }

        $pendingAvailability = $volunteers - $submittedAvailability;
        $availabilityPercentage = $volunteers > 0 
            ? round(($submittedAvailability / $volunteers) * 100, 2)
            : 0;

        return response()->json([
            'statistics' => [
                'volunteers' => $volunteers,
                'departments' => $departments,
                'divisions' => $divisions,
                'skills' => $skills,
                'schedule_periods' => $schedulePeriods
            ],
            'active_period' => [
                'period' => $activePeriod,
                'submitted' => $submittedAvailability,
                'pending' => $pendingAvailability,
                'percentage' => $availabilityPercentage
            ],
        ]);
    }
}
