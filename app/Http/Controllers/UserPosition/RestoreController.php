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

            $data = [
                'role_id' => $userPosition->role_id,
                'department_id' => $userPosition->department_id,
                'division_id' => $userPosition->division_id,
            ];

            if (UserPosition::positionExists($data)) {
                return response()->json([
                    'message' => 'Position is already occupied.'
                ], 422);
            }

            $userPosition->restore();

            return response()->json($userPosition);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
