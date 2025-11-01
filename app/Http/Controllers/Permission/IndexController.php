<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\PermissionsMeta;

class IndexController extends Controller
{
    public function __invoke()
    {
        $permissions = PermissionsMeta::query()
            ->select('module', 'menu', 'permission_name', 'action')
            ->get()
            ->groupBy(['module', 'menu']);

        return response()->json($permissions);
    }
}
