<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Exception;
use Illuminate\Support\Facades\DB;

class DestroyController extends Controller
{
    public function __invoke(Action $action)
    {
        if (!$action) abort(404);

        DB::beginTransaction();
        try {
            $action->modules()->detach();

            if ($action->trashed()) {
                $action->forceDelete();
            } else {
                $action->delete();
            }

            DB::commit();
            return response()->noContent();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
