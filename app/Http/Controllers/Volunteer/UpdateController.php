<?php

namespace App\Http\Controllers\Volunteer;

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

                $data['photo'] = $request->file('photo')->store('users', 'public');
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

            if (array_key_exists('skills', $data)) {

                $skillIds = collect($data['skills'])->pluck('skill_id')->toArray();

                $validSkillIds = \App\Models\Skill::whereIn('id', $skillIds)->pluck('id')->toArray();

                if (count($validSkillIds) !== count($skillIds)) {
                    throw new \Exception('Salah satu skill ID tidak valid.');
                }

                if (collect($data['skills'])->where('is_primary', true)->count() > 1) {
                    throw new \Exception('Hanya boleh satu skill utama.');
                }

                $payload = collect($data['skills'])->map(function ($skill) use ($user) {
                    return [
                        'user_id'    => $user->id,
                        'skill_id'   => $skill['skill_id'],
                        'is_primary' => $skill['is_primary'] ?? false,
                        'order'      => $skill['order'] ?? 0,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ];
                })->toArray();

                // upsert
                \App\Models\UserSkill::upsert(
                    $payload,
                    ['user_id', 'skill_id'], // unique constraint
                    ['is_primary', 'order', 'updated_at']
                );

                // delete yang tidak ada di request
                $user->skills()
                    ->whereNotIn('skill_id', $skillIds)
                    ->delete();
            }

            DB::commit();

            return response()->json($user->fresh()->load(['roles', 'departments', 'divisions', 'skills']));
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
