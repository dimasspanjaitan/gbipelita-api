<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // Auth Check
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json(['valid' => true,]);
    }

    // Register User
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('congregation');

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
        ]);
    }

    // Login User (Generate Bearer Token)
    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            filter_var($validated['identifier'], FILTER_VALIDATE_EMAIL)
                ? 'email'
                : 'username' => $validated['identifier'],
            'password' => $validated['password'],
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::user();
        $permissions = $user->getAllPermissions()->pluck('name');

        // Hapus semua token lama
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user->load('roles'),
            'permissions' => $permissions,
        ]);
    }

    // Get authenticated user
    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');

        return response()->json([
            'success' => true,
            'message' => 'Authenticated user retrieved successfully',
            'data' => [
                'user' => $user,
                'roles' => $roles,
                'permissions' => $permissions,
            ],
        ]);
    }

    // Logout (revoke current token)
    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No authenticated user'
            ], 401);
        }

        // Ambil token dari header Authorization
        $tokenString = $request->bearerToken();

        if (!$tokenString) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 400);
        }

        // Cek token-nya di database Sanctum
        $token = PersonalAccessToken::findToken($tokenString);

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout successful. Token has been revoked'
        ]);
    }
}
