<?php

namespace App\Http\Controllers\SchedulePeriods;

use App\Http\Controllers\Controller;
use App\Services\Scheduling\PeriodBuilderService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024',
            'department_id' => 'required|uuid',
        ]);

        try {
            $period = app(PeriodBuilderService::class)->build(
                $request->month,
                $request->year,
                $request->department_id
            );

            return response()->json([
                'success' => true,
                'message' => 'Schedule period created',
                'data' => $period,
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
