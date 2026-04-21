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
        Log::info('START GENERATE');
        DB::transaction(function () use ($period) {

            Log::info('BUILD CONTEXT');
            $context = app(ScheduleContextBuilder::class)
                ->build($period);

            Log::info('RUN SCHEDULER');
            $scheduler = app(DeterministicScheduler::class);
            // $scheduler = app(GeneticScheduler::class);

            $assignments = $scheduler->run($context);
            Log::info('ASSIGNMENTS COUNT: ' . count($assignments));
            Log::info('SAVE ASSIGNMENTS');
            // clear existing system generated assignments
            ScheduleAssignment::where('schedule_period_id', $period->id)
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
