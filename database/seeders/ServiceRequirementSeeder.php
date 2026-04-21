<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ServiceSession;
use App\Models\ServiceRequirement;
use App\Models\Skill;

class ServiceRequirementSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = ServiceSession::all();

        foreach ($sessions as $session) {

            // contoh skill id (ambil dari DB kamu)
            $requirements = [
                // Vocal
                ['division' => 'Vocal', 'skill' => 'WL', 'qty' => 1],
                ['division' => 'Vocal', 'skill' => 'Singer', 'qty' => 3],

                // Music
                ['division' => 'Musik', 'skill' => 'Piano', 'qty' => 1],
                ['division' => 'Musik', 'skill' => 'Bass', 'qty' => 1],
                ['division' => 'Musik', 'skill' => 'Guitar/Saxo', 'qty' => 1],
                ['division' => 'Musik', 'skill' => 'Drum', 'qty' => 1],

                // Multimedia
                ['division' => 'Multimedia', 'skill' => 'Camera', 'qty' => 2],
                ['division' => 'Multimedia', 'skill' => 'Slide', 'qty' => 2],
                ['division' => 'Multimedia', 'skill' => 'Lighting', 'qty' => 1],
                ['division' => 'Multimedia', 'skill' => 'Monitor', 'qty' => 1],

                // Choir
                ['division' => 'Choir', 'skill' => 'Choir', 'qty' => 5],
                ['division' => 'Sound System', 'skill' => 'Soundman', 'qty' => 1]
            ];

            foreach ($requirements as $req) {

                $divisionId = Division::where('name', $req['division'])->value('id');
                $skillId = Skill::where('name', $req['skill'])->value('id');

                if (!$divisionId || !$skillId) {
                    continue;
                }

                ServiceRequirement::create([
                    'id' => Str::uuid(),
                    'service_session_id' => $session->id,
                    'division_id' => $divisionId,
                    'skill_id' => $skillId,
                    'required_qty' => $req['qty'],
                ]);
            }
        }
    }
}