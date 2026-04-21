<?php

namespace App\Http\Controllers\SchedulePeriod;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchedulePeriod\UpdateRequest;
use App\Models\SchedulePeriod;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, SchedulePeriod $schedulePeriod)
    {
        DB::beginTransaction();
        
        try {
            $schedulePeriod->update($request->validated());
            DB::commit();

            return response()->json($schedulePeriod->fresh());
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
