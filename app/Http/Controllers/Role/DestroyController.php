<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class DestroyController extends Controller
{
    public function __invoke(Role $role)
    {
        $role->delete();
        return response()->noContent();
    }
}