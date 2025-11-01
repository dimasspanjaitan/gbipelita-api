<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $action = Action::onlyTrashed()->findOrFail($id);

        if (!$action) abort(404);

        try {
            $action->forceDelete();

            return response()->noContent();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
