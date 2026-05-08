<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleAssignment\BulkUpdateRequest;
use App\Models\ScheduleAssignment;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BulkUpdateController extends Controller
{
    public function __invoke(BulkUpdateRequest $request, SchedulePeriod $period): JsonResponse
    {
        DB::beginTransaction();

        try {

            $assignments = $request->validated()['assignments'];

            foreach ($assignments as $assignment) {
                ScheduleAssignment::query()
                    ->where('id', $assignment['id'])
                    ->where('schedule_period_id', $period->id)
                    ->update([
                        'user_id' => $assignment['user_id'],
                    ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Assignments updated successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
