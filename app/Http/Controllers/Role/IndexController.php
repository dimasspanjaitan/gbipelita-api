<?php

namespace App\Http\Controllers\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class IndexController extends Controller
{
    public function __invoke()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }
}