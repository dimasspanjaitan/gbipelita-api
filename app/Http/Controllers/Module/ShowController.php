<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;

class ShowController extends Controller
{
    public function __invoke(Module $module)
    {
        return response()->json($module->load('actions'));
    }
}
