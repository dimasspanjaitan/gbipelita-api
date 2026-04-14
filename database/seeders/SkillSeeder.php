<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua department yang dibutuhkan
        $divisions = Division::whereIn('name', ['Musik', 'Vocal', 'Multimedia'])
            ->get()
            ->keyBy('name');

        // Validasi ketersediaan
        if (!isset($divisions['Musik']) || !isset($divisions['Vocal']) || !isset($divisions['Multimedia'])) {
            $this->command->error('Pastikan DivisionSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $skills = [
            'Musik' => [
                ['name' => 'Piano'],
                ['name' => 'Filler'],
                ['name' => 'Bass'],
                ['name' => 'Guitar/Saxo'],
                ['name' => 'Drum'],
            ],
            'Vocal' => [
                ['name' => 'WL'],
                ['name' => 'Singer']
            ],
            'Multimedia' => [
                ['name' => 'Slide'],
                ['name' => 'Camera'],
                ['name' => 'Lighting'],
                ['name' => 'Monitor'],
            ],
        ];

        foreach ($skills as $divs => $items) {
            $divisionId = $divisions[$divs]->id;

            foreach ($items as $item) {
                Skill::create(array_merge($item, [
                    'division_id' => $divisionId,
                ]));
            }
        }

        $this->command->info('Skills seeded successfully.');
    }
}
