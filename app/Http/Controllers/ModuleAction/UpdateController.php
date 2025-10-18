<?php

namespace App\Http\Controllers\ModuleAction;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleAction\UpdateRequest;
use App\Models\ModuleAction;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, ModuleAction $moduleAction)
    {
        DB::beginTransaction();

        try {
            $moduleAction->update($request->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Module action updated successfully.',
                'data' => $moduleAction->fresh('module'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update module action.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
