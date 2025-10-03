<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreRequest;
use App\Models\Department;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware permission.
     */
    public function __construct()
    {
        // Permission untuk menambah department
        $this->middleware('permission:master_departments_add');
    }

    /**
     * Menyimpan Department baru (POST /departments)
     */
    public function __invoke(StoreRequest $request)
    {
        try {
            $data = $request->validated();

            $department = Department::create([
                'id' => Str::uuid(),
                'name' => $data["name"],
                'content' => $data["content"],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department berhasil ditambahkan.',
                'data' => $department
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan department: ' . $e->getMessage()
            ], 500);
        }
    }
}
