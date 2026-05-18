<?php

namespace App\Services\Scheduling;

use App\Models\SchedulePeriod;
use App\Models\User;

class ScheduleContextBuilder
{
    public function build(SchedulePeriod $period): array
    {
        $period->load([
            'sessions.requirements',
            'sessions.assignments',
            'userStatuses',
        ]);

        $users = User::with([
            'skills',
            'availabilities' => fn($q) =>
            $q->where('schedule_period_id', $period->id),
        ])
            ->where('status', 'active')
            ->get();

        $context = [
            'period' => [
                'id' => $period->id,
                'max_per_week' => 2,
                'max_overflow_per_week' => 3,
            ],
            'sessions' => [],
            'requirements' => [],
            'users' => [],
            'availability' => [],
            'user_submission' => [],
            'tracking' => [
                'weekly_load' => [],
                'department_load' => [],
                'session_assignments' => [],
            ],
        ];

        // Sessions & Requirements
        foreach ($period->sessions as $session) {

            $context['sessions'][$session->id] = [
                'id' => $session->id,
                'week' => $session->week_number,
                'session_number' => $session->session_number,
                'requirements' => [],
            ];

            foreach ($session->requirements as $req) {

                $context['requirements'][$req->id] = [
                    'id' => $req->id,
                    'session_id' => $session->id,
                    'division_id' => $req->division_id,
                    'skill_id' => $req->skill_id,
                    'required_qty' => $req->required_qty,
                    'assigned' => 0,
                ];

                $context['sessions'][$session->id]['requirements'][] = $req->id;
            }
        }

        // Users
        foreach ($users as $user) {

            $context['users'][$user->id] = [
                'id' => $user->id,
                'department_id' => $user->department_id,
                'skills' => [],
            ];

            foreach ($user->skills as $skill) {
                $context['users'][$user->id]['skills'][$skill->id] = [
                    'is_primary' => $skill->pivot->is_primary,
                    'order' => $skill->pivot->order ?? 0,
                ];
            }

            // availability
            foreach ($user->availabilities as $avail) {
                if ($avail->is_available) {
                    $context['availability'][$user->id][$avail->service_session_id] = true;
                }
            }

            // submission
            $status = $period->userStatuses
                ->firstWhere('user_id', $user->id);

            $context['user_submission'][$user->id] =
                $status?->has_submitted ?? false;

            $context['tracking']['weekly_load'][$user->id] = [];
            $context['tracking']['department_load'][$user->department_id][$user->id] = 0;
        }

        return $context;
    }
}
