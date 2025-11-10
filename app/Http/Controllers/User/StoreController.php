<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            /** @var \Illuminate\Http\Request $request */
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('users', 'public');
            }

            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            // Sync roles
            if (!empty($data['roles'])) {
                $validRoles = Role::whereIn('id', $data['roles'])->pluck('id')->toArray();
                
                if (count($validRoles) !== count($data['roles'])) {
                    throw new Exception('Salah satu role ID tidak valid.');
                }
                
                $user->syncRoles($validRoles);
            } else {
                $congregationRole = Role::where('name', 'congregation')->first();
                if ($congregationRole) {
                    $user->assignRole($congregationRole->id);
                }
            }

            // Sync departments
            if (!empty($data['departments'])) {
                $user->departments()->sync($data['departments']);
            }

            // Sync divisions
            if (!empty($data['divisions'])) {
                $user->divisions()->sync($data['divisions']);
            }

            DB::commit();

            return response()->json($user->load(['roles', 'departments', 'divisions']));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}