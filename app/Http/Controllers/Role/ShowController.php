<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class ShowController extends Controller
{
    public function __invoke(string $id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail role berhasil dimuat.',
            'data' => $role
        ]);
    }
}
