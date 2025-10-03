<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class ForceDeleteController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware permission.
     */
    public function __construct()
    {
        // Permission untuk menghapus permanen (biasanya untuk super admin)
        $this->middleware('permission:master_departments_delete'); 
    }

    /**
     * Menghapus Department secara permanen (DELETE /departments/force-delete/{id})
     */
    public function __invoke(string $id)
    {
        // Mencari department (termasuk yang sudah dihapus, menggunakan withTrashed())
        $department = Department::withTrashed()->find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department tidak ditemukan.'
            ], 404);
        }

        try {
            // Melakukan hapus permanen
            $department->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Department berhasil dihapus permanen.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen department: ' . $e->getMessage()
            ], 500);
        }
    }
}
