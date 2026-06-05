<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchedulePeriod\StoreRequest;
use App\Models\SchedulePeriod;
use App\Services\Scheduling\PeriodBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        try {
            $schedulePeriod = SchedulePeriod::create($request->validated());
            return response()->json($schedulePeriod);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
