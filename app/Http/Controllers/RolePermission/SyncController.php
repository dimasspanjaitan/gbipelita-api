<?php

namespace App\Http\Controllers\RolePermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SyncController extends Controller
{
    public function __invoke(Request $request, Role $role)
    {
        $request->validate(['permissions' => 'required|array']);
        
        $role->syncPermissions($request->permissions);
        
        return response()->json([
            'message' => 'Permissions updated successfully.',
            'role' => $role->load('permissions')
        ]);
    }
}