<?php

namespace App\Http\Controllers\SchedulePeriods;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;

class DestroyController extends Controller
{
    public function __invoke(SchedulePeriod $schedulePeriod)
    {
        $schedulePeriod->delete();
        return response()->noContent();
    }
}
