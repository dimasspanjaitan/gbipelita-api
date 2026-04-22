<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;

class OpenController extends Controller
{
    public function __invoke(SchedulePeriod $schedulePeriod)
    {
        if ($schedulePeriod->status != 'draft') {
            return response()->json([
                'success' => false,
                'message' => "Only draft can be opened"
            ], 422);
        }

        $schedulePeriod->update([
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule period opened successfully',
            'data' => $schedulePeriod,
        ]);
    }
}
