<?php

namespace App\Services\Scheduling;

use App\Models\SchedulePeriod;
use App\Models\ScheduleUserPeriodStatus;
use App\Models\ServiceSession;
use App\Models\ServiceRequirement;
use App\Models\Skill;
use App\Models\User;
use App\Services\UserVolunteerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PeriodBuilderService
{
    public function __construct(
        protected UserVolunteerService $userVolunteerService
    ) {}

    public function build(int $month, int $year, string $departmentId): SchedulePeriod
    {
        return DB::transaction(function () use ($month, $year, $departmentId) {

            // ❗ prevent duplicate period
            $existing = SchedulePeriod::query()
                ->where([
                    'department_id' => $departmentId,
                    'month' => $month,
                    'year' => $year,
                ])->first();

            if ($existing) {
                throw new \Exception('Schedule period already exists');
            }

            $period = SchedulePeriod::create([
                'id' => Str::uuid(),
                'department_id' => $departmentId,
                'month' => $month,
                'year' => $year,
                'status' => 'draft',
            ]);

            $sessions = $this->generateSessions($period);

            $this->generateRequirements($sessions);

            $this->generateUserStatuses($period);

            return $period;
        });
    }

    protected function generateSessions(SchedulePeriod $period): array
    {
        $start = Carbon::create($period->year, $period->month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $sessions = [];
        $week = 1;

        $sessionTemplates = [
            [
                'name' => 'IBRA 1&2 (PAGI)',
                'time' => '(09:00-10:30) & (11:00-12:30)',
                'start' => '09:00',
                'end' => '12:30',
            ],
            [
                'name' => 'IBRA 3&4 (SORE)',
                'time' => '(14:00-15:30) & (16:00-17:30)',
                'start' => '14:00',
                'end' => '17:30',
            ],
            [
                'name' => 'IBRA 5&6 (MALAM)',
                'time' => '(18:00-19:30) & (20:00-21:30)',
                'start' => '18:00',
                'end' => '21:30',
            ],
        ];

        foreach (CarbonPeriod::create($start, $end) as $date) {

            if ($date->dayOfWeek !== Carbon::SUNDAY) continue;

            foreach ($sessionTemplates as $index => $template) {

                $sessions[] = ServiceSession::create([
                    'id' => Str::uuid(),
                    'name' => $template['name'],
                    'time' => $template['time'],
                    'schedule_period_id' => $period->id,
                    'service_date' => $date->toDateString(),
                    'week_number' => $week,
                    'session_number' => $index + 1, // 1,2,3
                    'start_time' => $template['start'],
                    'end_time' => $template['end'],
                ]);
            }

            $week++;
        }

        return $sessions;
    }

    protected function generateRequirements(array $sessions): void
    {
        $skills = Skill::all();

        foreach ($sessions as $session) {

            foreach ($skills as $skill) {

                ServiceRequirement::updateOrCreate(
                    [
                        'service_session_id' => $session->id,
                        'skill_id' => $skill->id,
                    ],
                    [
                        'id' => Str::uuid(),
                        'division_id' => $skill->division_id,
                        'required_qty' => $this->defaultQty($skill),
                    ]
                );
            }
        }
    }

    protected function defaultQty(Skill $skill): int
    {
        return match ($skill->name) {
            'Singer' => 2,
            'Camera' => 2,
            default => 1,
        };
    }

    protected function generateUserStatuses(
        SchedulePeriod $period
    ): void {

        $volunteers = $this->userVolunteerService
            ->getDepartmentVolunteers($period->department_id)
            ->pluck('id');

        $now = now();
        $rows = [];

        foreach ($volunteers as $userId) {
            $rows[] = [
                'id' => Str::uuid(),
                'schedule_period_id' => $period->id,
                'user_id' => $userId,
                'has_submitted' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($rows)) {
            ScheduleUserPeriodStatus::insert($rows);
        }
    }
}
