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

            return response()->json($action->fresh('modules'));
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
