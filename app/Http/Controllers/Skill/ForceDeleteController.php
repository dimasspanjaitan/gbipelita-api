<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Models\Skill;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $skill = Skill::onlyTrashed()->findOrFail($id);
        $skill->forceDelete();

        return response()->noContent();
    }
}
