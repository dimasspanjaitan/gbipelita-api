<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Division;

class ShowController extends Controller
{
    
    public function __invoke(Division $division)
    {
        return response()->json($division->load('department'));
    }
}
