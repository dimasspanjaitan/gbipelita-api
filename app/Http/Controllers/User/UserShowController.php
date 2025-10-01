<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserShowController extends Controller
{
    public function __invoke(User $user): JsonResponse
    {
        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user->load('roles'),
        ]);
    }
}
