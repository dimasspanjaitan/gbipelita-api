<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua department yang dibutuhkan
        $department = Department::query()
            ->where('alias', 'EW')
            ->first();

        // Validasi ketersediaan
        if (!$department) {
            $this->command->error('Pastikan DepartmentSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $divisions = [
            ['name' => 'Music'],
            ['name' => 'Choir'],
            ['name' => 'Vocal'],
            ['name' => 'Multimedia', 'alias' => 'Mulmed'],
            ['name' => 'Sound System'],
        ];

        foreach ($divisions as $division) {
            Division::create([
                ...$division,
                'department_id' => $department->id,
                'status' => 'active',
            ]);
        }

        $this->command->info('Divisions seeded successfully.');
    }
}
