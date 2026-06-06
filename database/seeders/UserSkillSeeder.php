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

        foreach ($users as $user) {
            $userSkills = collect();
            $manualSkills = [
                'laora' => 'Slide',
                'riris' => 'Singer'
            ];

            // Manual assignment
            if (isset($manualSkills[$user->username])) {
                $skill = Skill::query()
                    ->where('name', $manualSkills[$user->username])
                    ->first();

                if ($skill) $userSkills->push($skill);
            } else {
                foreach ($user->divisions as $division) {

                    $skills = $skillsByDivision[$division->id] ?? collect();

                    // Skip kalau tidak ada skill
                    if ($skills->isEmpty()) {
                        $this->command->warn("Division {$division->name} tidak punya skill.");
                        continue;
                    }

                    $userSkills->push(
                        $skills->random()
                    );
                }
            }

            $userSkills = $userSkills->unique('id')->values();

            if ($userSkills->isEmpty()) continue;

            $primarySkill = $userSkills->random();
            $order = 1;

            UserSkill::create([
                'user_id' => $user->id,
                'skill_id' => $primarySkill->id,
                'is_primary' => true,
                'order' => $order++,
            ]);

            foreach ($userSkills->where('id', '!=', $primarySkill->id) as $skill) {
                UserSkill::create([
                    'user_id' => $user->id,
                    'skill_id' => $skill->id,
                    'is_primary' => false,
                    'order' => $order++
                ]);
            }
        }

        $this->command->info('UserSkill seeding completed.');
    }
}
