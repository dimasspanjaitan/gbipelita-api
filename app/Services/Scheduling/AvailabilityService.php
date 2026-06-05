<?php

namespace App\Services\Scheduling;

use App\Models\ScheduleAvailability;
use App\Models\ScheduleUserPeriodStatus;
use Illuminate\Support\Facades\DB;

class AvailabilityService
{
    public function submit(string $periodId, string $userId, array $sessionIds, string $notes): void
    {
        DB::transaction(function () use ($periodId, $userId, $sessionIds, $notes) {

            // delete existing
            ScheduleAvailability::query()
                ->where('schedule_period_id', $periodId)
                ->where('user_id', $userId)
                ->delete();

            // insert new
            $rows = [];

            foreach ($sessionIds as $sessionId) {
                $rows[] = [
                    'id' => \Str::uuid(),
                    'schedule_period_id' => $periodId,
                    'service_session_id' => $sessionId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ScheduleAvailability::insert($rows);

            // mark submitted
            ScheduleUserPeriodStatus::updateOrCreate(
                [
                    'schedule_period_id' => $periodId,
                    'user_id' => $userId,
                ],
                [
                    'has_submitted' => true,
                    'notes' => $notes
                ]
            );
        });
    }
}
