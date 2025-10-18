<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);

        try {
            $role->forceDelete();

            return response()->json([
                'message' => 'Role berhasil dihapus permanen.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
