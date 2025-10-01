<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserIndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', 10);
        $limit = $limit > 0 ? min($limit, 100) : 10;

        $queryUsers = User::with('roles');

        if ($request->query('trashed') === 'only') {
            $queryUsers->onlyTrashed();
        } elseif ($request->query('trashed') === 'with') {
            $queryUsers->withTrashed();
        }

        $users = $queryUsers->paginate($limit);

        return response()->json($users);
    }
}
