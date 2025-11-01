<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return response()->noContent();
    }
}
