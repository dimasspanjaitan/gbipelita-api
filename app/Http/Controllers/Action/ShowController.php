<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;

class ShowController extends Controller
{
    public function __invoke(Action $action)
    {
        return response()->json($action);
    }
}
