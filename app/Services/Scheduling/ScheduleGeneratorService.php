<?php

namespace App\Services\Scheduling;

use App\Models\SchedulePeriod;
use App\Models\ScheduleAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleGeneratorService
{
    public function generate(SchedulePeriod $period): void
    {
        DB::transaction(function () use ($period) {

            $context = app(ScheduleContextBuilder::class)
                ->build($period);

            $scheduler = app(GeneticScheduler::class);
            $assignments = $scheduler->run($context);
            
            // clear existing system generated assignments
            ScheduleAssignment::query()
                ->where('schedule_period_id', $period->id)
                ->where('is_system_generated', true)
                ->delete();

            foreach ($assignments as $assignment) {
                ScheduleAssignment::create([
                    'schedule_period_id' => $period->id,
                    'service_session_id' => $assignment['session_id'],
                    'service_requirement_id' => $assignment['requirement_id'],
                    'user_id' => $assignment['user_id'],
                    'is_system_generated' => true,
                ]);
            }
        });
    }
}
