<?php

namespace App\Http\Controllers\UserPosition;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPosition\StoreRequest;
use App\Models\UserPosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $userPosition = UserPosition::create($request->validated());
            DB::commit();

            return response()->json($userPosition->load(['user', 'role', 'department', 'division']));
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
