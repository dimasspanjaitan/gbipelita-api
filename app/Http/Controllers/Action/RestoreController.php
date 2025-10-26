<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        $action = Action::withTrashed()->find($id);

        if (!$action || !$action->trashed()) {
            return response()->json([
                'message' => 'Action not found or not deleted.',
            ], 404);
        }

        try {
            $action->restore();

            return response()->json([
                'message' => 'Action restored successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
