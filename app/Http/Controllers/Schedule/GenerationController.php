<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateScheduleJob;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;

class GenerationController extends Controller
{
    public function generate(SchedulePeriod $period): JsonResponse
    {
        if ($period->status === 'generating') {
            return response()->json([
                'message' => 'Schedule is already generating.'
            ], 409);
        }

        $period->update([
            'status' => 'generating'
        ]);

        GenerateScheduleJob::dispatch($period->id);

        return response()->json([
            'message' => 'Schedule generated started.'
        ]);
    }
}
