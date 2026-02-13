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

    public SchedulePeriod $period;

    public function __construct(SchedulePeriod $period)
    {
        $this->period = $period;
    }

    public function handle(): void
    {
        $this->period->update(['status' => 'generating']);

        try {
            app(ScheduleGeneratorService::class)
                ->generate($this->period);

            $this->period->update(['status' => 'generated']);
        } catch (\Throwable $e) {
            $this->period->updated(['status' => 'failed']);

            Log::error('Schedule generation failed', [
                'period_id' => $this->period->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
