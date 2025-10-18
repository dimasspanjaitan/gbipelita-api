<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        $role = Role::withTrashed()->find($id);

        if (!$role || !$role->trashed()) {
            return response()->json([
                'message' => 'Role tidak ditemukan atau belum dihapus.'
            ], 404);
        }

        try {
            $role->restore();

            return response()->json([
                'message' => 'Role berhasil dipulihkan.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
