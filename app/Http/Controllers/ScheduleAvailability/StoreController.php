<?php

namespace App\Http\Controllers\ScheduleAvailability;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleAvailability\StoreRequest;
use App\Models\SchedulePeriod;
use App\Services\Scheduling\AvailabilityService;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, SchedulePeriod $period)
    {
        $this->validate($request, [
            'session_ids' => 'array',
            'notes' => 'nullable'
        ]);

        app(AvailabilityService::class)->submit(
            $period->id,
            auth()->id(),
            $request->session_ids ?? [],
            $request->notes ?? null
        );

        return response()->json([
            'message' => 'Availability saved'
        ]);
    }
}
