<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class DestroyController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware permission.
     */
    public function __construct()
    {
        // Permission untuk menghapus department (soft delete)
        $this->middleware('permission:master_departments_delete');
    }

    /**
     * Menghapus Department berdasarkan ID (DELETE /departments/{id})
     */
    public function __invoke(string $id)
    {
        // Mencari department yang TIDAK dihapus (default)
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department tidak ditemukan atau sudah dihapus.'
            ], 404);
        }

        try {
            // Melakukan soft delete (mengisi kolom deleted_at)
            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department berhasil dihapus (soft delete).'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus department: ' . $e->getMessage()
            ], 500);
        }
    }
}
