<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RestoreController extends Controller
{
    public function __invoke(string $id): JsonResponse
    {
        $volunteer = User::onlyTrashed()->findOrFail($id);
        $volunteer->restore();

        return response()->json($volunteer->load('roles'));
    }
}
