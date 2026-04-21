<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        try {
            $schedulePeriod = SchedulePeriod::onlyTrashed()->findOrFail($id);
            $schedulePeriod->restore();

            return response()->json($schedulePeriod);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
