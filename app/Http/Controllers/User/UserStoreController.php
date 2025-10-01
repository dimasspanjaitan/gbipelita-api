<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserStoreController extends Controller
{
    public function __invoke(UserStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        /** @var \Illuminate\Http\Request $request */
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // assign default role kalau ada input role
        if ($request->filled('role')) {
            $user->assignRole($request->role);
        } else {
            // bisa set default role misalnya "congregation"
            $user->assignRole('congregation');
        }

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user->load('roles'),
        ], 201);
    }
}
