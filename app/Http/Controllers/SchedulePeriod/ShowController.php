<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{

    public function __invoke(SchedulePeriod $period): JsonResponse
    {
        return response()->json($period->load('department'));
    }
}
