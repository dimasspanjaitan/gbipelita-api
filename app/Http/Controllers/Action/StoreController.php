<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\Action\StoreRequest;
use App\Models\Action;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $action = Action::create($request->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Action created successfully.',
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
