<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\UpdateRequest;
use App\Models\Department;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Department $department)
    {
        DB::beginTransaction();
        
        try {
            $department->update($request->validated());
            DB::commit();

            return response()->json($department)->fresh();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
