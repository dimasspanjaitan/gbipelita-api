<?php

namespace App\Http\Controllers\ModuleAction;

use App\Http\Controllers\Controller;
use App\Models\ModuleAction;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $action = ModuleAction::onlyTrashed()->find($id);

        if (!$action) {
            return response()->json([
                'message' => 'Module action not found.',
            ], 404);
        }

        try {
            $action->forceDelete();

            return response()->json([
                'message' => 'Module action permanently deleted.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
