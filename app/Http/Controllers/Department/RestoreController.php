<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class RestoreController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware permission.
     */
    public function __construct()
    {
        // Permission untuk restore (menggunakan permission edit/delete tingkat lanjut)
        $this->middleware('permission:master_departments_edit');
    }

    /**
     * Memulihkan Department yang sudah dihapus (POST /departments/restore/{id})
     */
    public function __invoke(string $id)
    {
        // Mencari department yang sudah dihapus (menggunakan withTrashed())
        $department = Department::withTrashed()->find($id);

        // Periksa apakah department ditemukan dan apakah sudah dihapus
        if (!$department || !$department->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Department tidak ditemukan atau belum dihapus.'
            ], 404);
        }

        try {
            // Melakukan restore (mengosongkan kolom deleted_at)
            $department->restore();

            return response()->json([
                'success' => true,
                'message' => 'Department berhasil dipulihkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan department: ' . $e->getMessage()
            ], 500);
        }
    }
}
