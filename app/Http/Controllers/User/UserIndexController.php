<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserIndexController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $users = User::with('roles')->paginate(10);

        return response()->json($users);
    }
}
