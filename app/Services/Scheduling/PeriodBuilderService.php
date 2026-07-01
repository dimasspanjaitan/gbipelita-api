<?php

namespace App\Services\Scheduling;

use App\Models\SchedulePeriod;
use App\Models\ScheduleUserPeriodStatus;
use App\Models\ServiceSession;
use App\Models\ServiceRequirement;
use App\Models\Skill;
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

    public function build(SchedulePeriod $period): SchedulePeriod
    {
        return DB::transaction(function () use ($period) {
            // schedule already open
            if ($period->status !== "draft") {
                throw new \Exception('Schedule period is already open');
            }

            $period->update([
                'status' => 'open',
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

        foreach (CarbonPeriod::create($start, $end) as $date) {

            if ($date->dayOfWeek !== Carbon::SUNDAY) continue;

            foreach ($period->session_templates as $index => $template) {
                $timeFormat = $template['is_split_session']
                    ? '(' . $template['start'] . '-' . $template['end'] . ') & (' . $template['start2'] . '-' . $template['end2'] . ')'
                    : $template['start'] . '-' . $template['end'];

                $sessions[] = ServiceSession::create([
                    'id' => Str::uuid(),
                    'name' => $template['name'],
                    'time' => $timeFormat,
                    'schedule_period_id' => $period->id,
                    'service_date' => $date->toDateString(),
                    'week_number' => $week,
                    'session_number' => $index + 1, // 1,2,3
                    'start_time' => $template['start'],
                    'end_time' => $template['is_split_session'] ? $template['end2'] : $template['end'],
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
