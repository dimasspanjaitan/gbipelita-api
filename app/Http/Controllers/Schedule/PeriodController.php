<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;

class PeriodController extends Controller
{
    public function assignments(SchedulePeriod $period): JsonResponse
    {
        $data = $period->assignments()
            ->with(['user', 'session', 'requirement'])
            ->get();

        return response()->json($data);
    }
}
