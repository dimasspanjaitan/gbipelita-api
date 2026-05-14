<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;

class PublishController extends Controller
{
    public function __invoke(SchedulePeriod $period)
    {
        if ($period->status != 'generated') {
            return response()->json([
                'success' => false,
                'message' => "Only generated can be published"
            ], 422);
        }

        $period->update([
            'status' => 'published',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule period published successfully',
            'data' => $period,
        ]);
    }
}
