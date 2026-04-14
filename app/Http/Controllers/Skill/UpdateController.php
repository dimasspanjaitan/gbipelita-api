<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\UpdateRequest;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Skill $skill)
    {
        DB::beginTransaction();
        
        try {
            $skill->update($request->validated());
            DB::commit();

            return response()->json($skill->fresh());
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
