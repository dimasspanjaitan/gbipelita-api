<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;
use App\Models\UserSkill;
use Illuminate\Support\Facades\DB;

class UserSkillSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_skills')->truncate();

        $users = User::with('divisions')->get();
        $skillsByDivision = Skill::all()->groupBy('division_id');

        $skillIndex = [];

        foreach ($users as $user) {
            foreach ($user->divisions as $division) {

                $skills = $skillsByDivision[$division->id] ?? collect();

                // Skip kalau tidak ada skill
                if ($skills->isEmpty()) {
                    $this->command->warn("Division {$division->name} tidak punya skill.");
                    continue;
                }

                // === 1. Primary skill (pasti ada) ===
                if (!isset($skillIndex[$division->id])) {
                    $skillIndex[$division->id] = 0;
                }

                $skill = $skills->values()[$skillIndex[$division->id] % $skills->count()];

                UserSkill::create([
                    'user_id' => $user->id,
                    'skill_id' => $skill->id,
                    'is_primary' => true,
                    'order' => 1,
                ]);

                $skillIndex[$division->id]++;

                // === 2. Secondary skill (HANYA jika skill > 1) ===
                if ($skills->count() > 1 && rand(0, 1)) {

                    $remainingSkills = $skills
                        ->where('id', '!=', $skill->id)
                        ->values();

                    if ($remainingSkills->isNotEmpty()) {
                        $extraSkill = $remainingSkills->random();

                        UserSkill::create([
                            'user_id' => $user->id,
                            'skill_id' => $extraSkill->id,
                            'is_primary' => false,
                            'order' => 2,
                        ]);
                    }
                }
            }
        }

        $this->command->info('UserSkill seeding completed.');
    }
}
