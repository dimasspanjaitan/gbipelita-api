<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    // Register User
    public function register(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // default role congregation
        $user->assignRole('congregation');

        return $user;
    }

    // Login User
    public function login(array $data): ?array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return null; // controller yang handle response error
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => $user->load('roles'),
        ];
    }

    // Logout User
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
