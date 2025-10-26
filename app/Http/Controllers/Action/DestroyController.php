<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;

class DestroyController extends Controller
{
    public function __invoke(Action $action)
    {
        $action->delete();
        return response()->noContent();
    }
}
