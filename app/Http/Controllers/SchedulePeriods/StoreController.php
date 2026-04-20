<?php

namespace App\Http\Controllers\SchedulePeriods;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchedulePeriod\StoreRequest;
use App\Services\Scheduling\PeriodBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        try {
            $schedulePeriod = app(PeriodBuilderService::class)->build(
                $request->month,
                $request->year,
                $request->department_id
            );

            return response()->json($schedulePeriod);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
