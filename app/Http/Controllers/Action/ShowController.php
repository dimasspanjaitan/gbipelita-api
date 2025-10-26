<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;

class ShowController extends Controller
{
    public function __invoke(string $id)
    {
        $action = Action::with('module')->find($id);

        if (!$action) {
            return response()->json([
                'message' => 'Action not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Action detail retrieved successfully.',
            'data' => $action,
        ]);
    }
}
