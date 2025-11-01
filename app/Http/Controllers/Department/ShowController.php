<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class ShowController extends Controller
{
    
    public function __invoke(Department $department)
    {
        return response()->json($department);
    }
}
