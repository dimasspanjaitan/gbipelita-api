<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Division;

class DestroyController extends Controller
{
    public function __invoke(Division $division)
    {
        $division->delete();
        return response()->noContent();
    }
}
