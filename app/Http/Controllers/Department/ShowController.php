<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class ShowController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware permission.
     */
    public function __construct()
    {
        // Permission untuk melihat detail department
        $this->middleware('permission:master_departments_view');
    }

    /**
     * Menampilkan detail Department berdasarkan ID (GET /departments/{id})
     */
    public function __invoke(string $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail department berhasil dimuat.',
            'data' => $department
        ]);
    }
}
