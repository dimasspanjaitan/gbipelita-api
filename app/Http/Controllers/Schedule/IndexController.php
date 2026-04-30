<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $schedules = SchedulePeriod::has('assignments')->get();

        return response()->json($schedules->load('department'));
    }
}
