<?php

namespace App\Http\Controllers\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
        $role->update(['name' => $request->name]);
        return response()->json($role);
    }
}