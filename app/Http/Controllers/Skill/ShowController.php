<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Models\Skill;

class ShowController extends Controller
{
    
    public function __invoke(Skill $skill)
    {


        return response()->json($skill->load('division'));
    }
}
