<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $department = Department::onlyTrashed()->findOrFail($id);
        $department->forceDelete();

        return response()->noContent();
    }
}
