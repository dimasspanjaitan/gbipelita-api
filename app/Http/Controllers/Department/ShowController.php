<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class ShowController extends Controller
{
    
    public function __invoke(string $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'message' => 'Department tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail department berhasil dimuat.',
            'data' => $department
        ]);
    }
}
