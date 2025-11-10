<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;

class ShowController extends Controller
{
    public function __invoke(Role $role)
    {
        return response()->json($role->load('permissions'));
    }
}
