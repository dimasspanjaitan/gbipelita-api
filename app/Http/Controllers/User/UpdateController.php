<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, User $user): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            /** @var \Illuminate\Http\Request $request */
            if ($request->hasFile('photo')) {
                // hapus foto lama jika ada
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $path = $request->file('photo')->store('users', 'public');

                $data['photo'] = Storage::disk('public')->url($path);
            }

            // Hash password jika diubah
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Update user data
            $user->update($data);

            // Sinkronisasi roles (kalau ada dikirim)
            if (!empty($data['roles'])) {
                $validRoles = Role::whereIn('id', $data['roles'])->pluck('id')->toArray();
                $user->syncRoles($validRoles); // Ke UUID role
            }

            // Sync departments (jika ada)
            if (!empty($data['departments'])) {
                $user->departments()->sync($data['departments']);
            }

            // Sync divisions (jika ada)
            if (!empty($data['divisions'])) {
                $user->divisions()->sync($data['divisions']);
            }

            DB::commit();

            return response()->json($user->fresh()->load(['roles', 'departments', 'divisions']));
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
