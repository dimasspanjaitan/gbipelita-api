<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SyncController extends Controller
{
    public function __invoke(Request $request, Role $role): JsonResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*.id' => ''
        ]);

        $role->syncPermissions($request->permissions);
        $role->getCollection()->load('permissions');

        return response()->json($role);
    }
}
