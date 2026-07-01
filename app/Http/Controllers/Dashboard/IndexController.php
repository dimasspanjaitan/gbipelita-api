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
            ->where('roles.name', 'Volunteer')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->where('status', 'active')
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
            ->with('department')
            ->latest()
            ->first();

        $submittedAvailability = 0;
        $notSubmittedAvailability = 0;

        if ($activePeriod->not_submitted_count == 0) {
            $submittedAvailability = DB::table('schedule_user_period_statuses')
                ->where('schedule_period_id', $activePeriod->id)
                ->where('has_submitted', true)
                ->distinct('user_id')
                ->count('user_id');

            $notSubmittedAvailability = $volunteers - $submittedAvailability;
        } else {
            $submittedAvailability = $activePeriod->submitted_count;
            $notSubmittedAvailability = $activePeriod->not_submitted_count;
        }

        $totalVolunteers = $submittedAvailability + $notSubmittedAvailability;
        $availabilityPercentage = round(($submittedAvailability / $totalVolunteers) * 100, 2);

        $availabilityChartData = [
            ['name' => 'submitted', 'value' => $submittedAvailability],
            ['name' => $activePeriod->status === "open" ? 'not_submitted' : "did_not_submit", 'value' => $notSubmittedAvailability]
        ];

        $startDate = now()
            ->subMonthsNoOverflow(3)
            ->startOfMonth();

        $endDate = now()
            ->subMonthNoOverflow()
            ->endOfMonth();

        $topVolunteers3Months = DB::table('schedule_assignments')
            ->join('users', 'users.id', '=', 'schedule_assignments.user_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'Volunteer')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->whereBetween('schedule_assignments.created_at', [
                $startDate,
                $endDate
            ])
            ->select(
                'users.id',
                'users.nickname',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"),
                DB::raw('COUNT(schedule_assignments.id) as total_assignments')
            )
            ->groupBy(
                'users.id',
                'users.nickname',
                DB::raw("users.first_name, users.last_name")
            )
            ->orderByDesc('total_assignments')
            ->limit(10)
            ->get();

        $topVolunteersThisYear = DB::table('schedule_assignments')
            ->join('users', 'users.id', '=', 'schedule_assignments.user_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'Volunteer')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->whereYear('schedule_assignments.created_at', now()->year)
            ->select(
                'users.id',
                'users.nickname',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"),
                DB::raw('COUNT(schedule_assignments.id) as total_assignments')
            )
            ->groupBy(
                'users.id',
                'users.nickname',
                DB::raw("users.first_name, users.last_name")
            )
            ->orderByDesc('total_assignments')
            ->limit(10)
            ->get();

        $totalSubmittedThisYear = DB::table('schedule_periods')
            ->where('schedule_periods.year', now()->year)
            ->select(
                'schedule_periods.month',
                'schedule_periods.submitted_count',
                'schedule_periods.not_submitted_count',
            )
            ->get();

        return response()->json([
            'stats' => [
                'volunteers' => $volunteers,
                'departments' => $departments,
                'divisions' => $divisions,
                'skills' => $skills,
                'schedule_periods' => $schedulePeriods,
                'top_volunteers_three_months' => $topVolunteers3Months,
                'top_volunteers_this_year' => $topVolunteersThisYear
            ],
            'active_schedule_period' => [
                'period' => $activePeriod,
                'percentage_submit' => $availabilityPercentage
            ],
            'charts' => [
                'availability' => $availabilityChartData,
                'submitted_this_year' => $totalSubmittedThisYear
            ]
        ]);
    }
}
