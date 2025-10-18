<?php

namespace App\Http\Controllers\ModuleAction;

use App\Http\Controllers\Controller;
use App\Models\ModuleAction;

class DestroyController extends Controller
{
    public function __invoke(ModuleAction $moduleAction)
    {
        $moduleAction->delete();
        return response()->noContent();
    }
}
