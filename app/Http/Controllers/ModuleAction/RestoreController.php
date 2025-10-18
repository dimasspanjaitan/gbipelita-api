<?php

namespace App\Http\Controllers\ModuleAction;

use App\Http\Controllers\Controller;
use App\Models\ModuleAction;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        $action = ModuleAction::withTrashed()->find($id);

        if (!$action || !$action->trashed()) {
            return response()->json([
                'message' => 'Module action not found or not deleted.',
            ], 404);
        }

        try {
            $action->restore();

            return response()->json([
                'message' => 'Module action restored successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
