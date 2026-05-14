<?php

namespace App\Jobs;

use App\Models\SchedulePeriod;
use App\Services\Scheduling\ScheduleGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $periodId;

    public function __construct(string $periodId)
    {
        $this->periodId = $periodId;
    }

    public function handle(): void
    {
        $period = SchedulePeriod::findOrFail($this->periodId);

        try {
            app(ScheduleGeneratorService::class)
                ->generate($period);

            $period->update(['status' => 'generated']);
        } catch (\Throwable $e) {

            $period->update(['status' => 'failed']);

            Log::error('Schedule generation failed', [
                'period_id' => $this->periodId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
