<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\UpdateRequest;
use App\Models\Department;

class UpdateController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware permission.
     */
    public function __construct()
    {
        // Permission untuk memperbarui department
        $this->middleware('permission:master_departments_edit');
    }

    /**
     * Memperbarui Department berdasarkan ID (PUT/PATCH /departments/{id})
     */
    public function __invoke(UpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department tidak ditemukan.'
            ], 404);
        }

        try {
            $department->update($data);
            $department->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Department berhasil diperbarui.',
                'data' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui department: ' . $e->getMessage()
            ], 500);
        }
    }
}
