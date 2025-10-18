<?php

namespace App\Http\Controllers\ModuleAction;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleAction\StoreRequest;
use App\Models\ModuleAction;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $action = ModuleAction::create($request->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Module action created successfully.',
                'data' => $action->load('module'),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create module action.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
