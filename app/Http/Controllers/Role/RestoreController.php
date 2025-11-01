<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        try {
            $role = Role::onlyTrashed()->findOrFail($id);
            $role->restore();

            return response()->json($role);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
