<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;

class IndexController extends Controller
{
    public function __invoke()
    {
        $modules = Module::with(['permissionsMetas' => function ($query) {
            $query->select('id', 'module_id', 'menu', 'permission_name', 'action', 'route_name');
        }])->get(['id', 'name', 'description']);

        return response()->json([
            'success' => true,
            'message' => 'List of modules with permissions',
            'data' => $modules,
        ]);
    }
}
