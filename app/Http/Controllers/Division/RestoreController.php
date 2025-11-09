<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Division;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        try {
            $division = Division::onlyTrashed()->findOrFail($id);
            $division->restore();

            return response()->json($division);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
