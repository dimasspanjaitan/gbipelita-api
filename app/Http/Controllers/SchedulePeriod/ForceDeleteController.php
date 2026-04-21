<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $schedulePeriod = SchedulePeriod::onlyTrashed()->findOrFail($id);
        $schedulePeriod->forceDelete();

        return response()->noContent();
    }
}
