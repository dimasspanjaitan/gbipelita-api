<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Division;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $division = Division::onlyTrashed()->findOrFail($id);
        $division->forceDelete();

        return response()->noContent();
    }
}
