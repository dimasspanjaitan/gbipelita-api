<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Models\Skill;

class DestroyController extends Controller
{
    public function __invoke(Skill $skill)
    {
        $skill->delete();
        return response()->noContent();
    }
}
