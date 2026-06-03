<?php

namespace App\Http\Controllers\UserPosition;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPosition\UpdateRequest;
use App\Models\UserPosition;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, UserPosition $userPosition)
    {
        DB::beginTransaction();

        try {
            $userPosition->update($request->validated());
            DB::commit();

            return response()->json($userPosition->fresh());
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
