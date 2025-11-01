<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        try {
            $department = Department::onlyTrashed()->findOrFail($id);
            $department->restore();

            return response()->json($department->load('divisions'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
