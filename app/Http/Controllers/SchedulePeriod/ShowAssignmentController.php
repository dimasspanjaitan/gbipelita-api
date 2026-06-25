<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ShowAssignmentController extends Controller
{
    public function __invoke(SchedulePeriod $period): JsonResponse
    {
        $users = User::query()
            ->withWhereHas('schedulePeriodStatuses', function ($q) use ($period) {
                $q->where('schedule_period_id', $period->id);
            })
            ->get();

        $submittedUsers = $users->filter(function ($user) {
            return $user->schedulePeriodStatuses->first()->has_submitted === true; // atau 1
        });

        $notSubmittedUsers = $users->filter(function ($user) {
            return $user->schedulePeriodStatuses->first()->has_submitted === false; // atau 0
        });

        $scheduleAssignments = [];
        if ($period->status == "generated" || $period->status == "published") {
            $scheduleAssignments = $period->assignments()->with(['session', 'user', 'requirement.skill'])->get();
        }

        return response()->json([
            'schedule_period' => [
                'id' => $period->id,
                'month' => $period->month,
                'year' => $period->year,
                'status' => $period->status,
                'department' => [
                    'id' => $period->department->id,
                    'name' => $period->department->name,
                ],
            ],
            'service_sessions' => $period->sessions->map(fn($session) => ([
                'id' => $session->id,
                'name' => $session->name,
                'time' => $session->time,
                'service_date' => $session->service_date,
                'week_number' => $session->week_number,
                'session_number' => $session->session_number,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
            ])),
            'submitted_users' => $submittedUsers->load('availabilities.session')
                ->sortBy(function ($user) {
                    return $user->schedulePeriodStatuses->first()?->updated_at;
                })
                ->values()
                ->map(fn($submittedUser) => ([
                    'id' => $submittedUser->id,
                    'username' => $submittedUser->username,
                    'nickname' => $submittedUser->nickname,
                    'full_name' => $submittedUser->full_name,
                    'email' => $submittedUser->email,
                    'status' => $submittedUser->status,
                    'photo' => $submittedUser->photo,
                    'phone' => $submittedUser->phone,
                    'address' => $submittedUser->address,
                    'birth_date' => $submittedUser->birth_date,
                    'notes' => $submittedUser->schedulePeriodStatuses->first()?->notes,
                    'availabilities' => $submittedUser->availabilities->map(fn($availability) => ([
                        'id' => $availability->id,
                        'is_available' => $availability->is_available,
                        'session' => [
                            'id' => $availability->session->id
                        ],
                    ])),
                ])),
            'not_submitted_users' => $notSubmittedUsers->values()->map(fn($notSubmittedUser) => ([
                'id' => $notSubmittedUser->id,
                'username' => $notSubmittedUser->username,
                'nickname' => $notSubmittedUser->nickname,
                'full_name' => $notSubmittedUser->full_name,
                'email' => $notSubmittedUser->email,
                'status' => $notSubmittedUser->status,
                'photo' => $notSubmittedUser->photo,
                'phone' => $notSubmittedUser->phone,
                'address' => $notSubmittedUser->address,
                'birth_date' => $notSubmittedUser->birth_date,
            ])),
            'assignments' => count($scheduleAssignments) > 0 ? $scheduleAssignments->map(fn($assignment) => ([
                'id' => $assignment->id,
                'session' => [
                    'id' => $assignment->session->id,
                    'name' => $assignment->session->name,
                    'time' => $assignment->session->time,
                    'start_time' => $assignment->session->start_time,
                    'end_time' => $assignment->session->end_time,
                    'week_number' => $assignment->session->week_number,
                    'session_number' => $assignment->session->session_number,
                ],
                'user' => [
                    'id' => $assignment->user->id,
                    'username' => $assignment->user->username,
                    'nickname' => $assignment->user->nickname,
                    'full_name' => $assignment->user->full_name,
                    'email' => $assignment->user->email,
                    'status' => $assignment->user->status,
                    'photo' => $assignment->user->photo,
                    'phone' => $assignment->user->phone,
                    'address' => $assignment->user->address,
                    'birth_date' => $assignment->user->birth_date,
                ],
                'requirement' => [
                    'skill' => [
                        'name' => $assignment->requirement->skill->name
                    ]
                ]
            ])) : []
        ]);
    }
}
