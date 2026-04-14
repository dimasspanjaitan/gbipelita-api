<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Models\Skill;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        try {
            $skill = Skill::onlyTrashed()->findOrFail($id);
            $skill->restore();

            return response()->json($skill);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
