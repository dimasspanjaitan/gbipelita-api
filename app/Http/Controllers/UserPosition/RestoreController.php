<?php

namespace App\Http\Controllers\UserPosition;

use App\Http\Controllers\Controller;
use App\Models\UserPosition;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        try {
            $userPosition = UserPosition::onlyTrashed()->findOrFail($id);
            $userPosition->restore();

            return response()->json($userPosition);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
