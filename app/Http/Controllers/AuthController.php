<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('congregation');

        return response()->json(['message' => 'User registered successfully']);
    }

    // Login User
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

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'data' => Auth::user()->load('roles'),
        ]);
    }

    // Get authenticated user
    public function me(Request $request)
    {
        $user = $request->user();

        // Ambil roles dan permissions dari Spatie Permission
        $roles = $user->getRoleNames(); // array of role names
        $permissions = $user->getAllPermissions()->pluck('name'); // collection of permission names

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


    // Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil!',
        ]);
    }
}
