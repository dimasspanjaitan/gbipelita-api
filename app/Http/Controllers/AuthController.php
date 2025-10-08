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
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required',
        ]);

        $identifier = $request->identifier;

        // Otentikasi user dengan username atau email
        if (!Auth::attempt(['username' => $identifier, 'password' => $request->password]) && !Auth::attempt(['email' => $identifier, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        // Hapus semua token lama untuk user ini
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user->load('roles'),
            'message' => 'Login berhasil!'
        ])->cookie('token', $token, 60 * 24 * 7, null, null, true, true);
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
        // Cek apakah pengguna terotentikasi dan memiliki sesi aktif.
        if (Auth::check()) {
            // Hapus sesi pengguna saat ini.
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Hapus cookie dari browser.
            return response()->json([
                'message' => 'Logout berhasil.'
            ])->withoutCookie('token');
        }

        // Jika tidak ada sesi atau pengguna, kirim respons 401.
        return response()->json([
            'message' => 'Tidak terotentikasi.'
        ], 401)->withoutCookie('token');
    }
}
