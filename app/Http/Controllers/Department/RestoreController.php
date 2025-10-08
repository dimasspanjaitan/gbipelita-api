<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        $department = Department::withTrashed()->find($id);

        if (!$department || !$department->trashed()) {
            return response()->json([
                'message' => 'Department tidak ditemukan atau belum dihapus.'
            ], 404);
        }

        try {
            $department->restore();

            return response()->json([
                'message' => 'Department berhasil dipulihkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
