<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreRequest;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $department = Department::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully.',
                'data' => $department,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create department.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
