<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\Action\UpdateRequest;
use App\Models\Action;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Action $action)
    {
        DB::beginTransaction();

        try {
            $action->update($request->validated());
            DB::commit();

            return response()->json($action->fresh('module'));
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
