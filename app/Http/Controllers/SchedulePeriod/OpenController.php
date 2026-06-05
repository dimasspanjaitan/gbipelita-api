<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use App\Services\Scheduling\PeriodBuilderService;

class OpenController extends Controller
{
    public function __invoke(SchedulePeriod $period)
    {
        if ($period->status != 'draft') {
            return response()->json([
                'success' => false,
                'message' => "Only draft can be opened"
            ], 422);
        }

        $period = app(PeriodBuilderService::class)->build($period);

        return response()->json([
            'success' => true,
            'message' => 'Schedule period opened successfully',
            'data' => $period,
        ]);
    }
}
