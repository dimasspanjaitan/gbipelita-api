<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $action = Action::onlyTrashed()->find($id);

        if (!$action) {
            return response()->json([
                'message' => 'Action not found.',
            ], 404);
        }

        try {
            $action->forceDelete();

            return response()->json([
                'message' => 'Action permanently deleted.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
