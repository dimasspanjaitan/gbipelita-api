<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('nickname', 'like', $search)
                        ->orWhere('username', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->orderByDesc('created_at')
            ->paginate($request->limit ?? 10)
            ->through(fn($user) => [
                'id' => $user->id,
                'username' => $user->username,
                'nickname' => $user->nickname,
                'full_name' => $user->full_name,
                'photo' => $user->photo,
            ]);

        return response()->json($users);
    }
}
