<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class DestroyController extends Controller
{
    public function __invoke(Department $department)
    {
        $department->delete();
        return response()->noContent();
    }
}
