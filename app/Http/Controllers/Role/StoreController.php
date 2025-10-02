<?php

namespace App\Http\Controllers\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        $role = Role::create(['name' => $request->name]);
        return response()->json($role, 201);
    }
}