<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    public function __invoke(User $user): JsonResponse
    {
        return response()->json($user->load('roles', 'departments', 'divisions'));
    }
}
