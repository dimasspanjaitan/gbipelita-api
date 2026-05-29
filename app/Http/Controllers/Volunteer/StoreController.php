<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Volunteer\StoreRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Skill;
use App\Models\UserSkill;
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

            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);


            // Sync roles
            $volunteerRoleId = Role::where('name', 'volunteer')->value('id');
            if ($volunteerRoleId) {
                $user->assignRole($volunteerRoleId);
            }

            // Sync departments
            if (!empty($data['departments'])) {
                $user->departments()->sync($data['departments']);
            }

            // Sync divisions
            if (!empty($data['divisions'])) {
                $user->divisions()->sync($data['divisions']);
            }

            // Sync skills
            if (!empty($data['skills'])) {
                $skillIds = collect($data['skills'])->pluck('skill_id')->toArray();

                // Validasi skill
                $validSkillIds = Skill::whereIn('id', $skillIds)->pluck('id')->toArray();

                if (count($validSkillIds) !== count($skillIds)) {
                    throw new Exception('Salah satu skill ID tidak valid.');
                }

                // Hapus existing (kalau ada)
                UserSkill::where('user_id', $user->id)->delete();

                $userSkills = collect($data['skills'])->map(function ($skill) {
                    return [
                        'skill_id'   => $skill['skill_id'],
                        'is_primary' => $skill['is_primary'] ?? false,
                        'order'      => $skill['order'] ?? 0,
                    ];
                })->toArray();
                
                $user->userSkills()->createMany($userSkills);
            }

            DB::commit();

            return response()->json($user->load(['roles', 'departments', 'divisions', 'skills']));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
