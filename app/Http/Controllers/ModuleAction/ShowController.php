<?php

namespace App\Http\Controllers\ModuleAction;

use App\Http\Controllers\Controller;
use App\Models\ModuleAction;

class ShowController extends Controller
{
    public function __invoke(string $id)
    {
        $action = ModuleAction::with('module')->find($id);

        if (!$action) {
            return response()->json([
                'message' => 'Module action not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Module action detail retrieved successfully.',
            'data' => $action,
        ]);
    }
}
