<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\StoreRequest;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $skill = Skill::create($request->validated());
            DB::commit();

            return response()->json($skill);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
