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
        $departments = Department::whereIn('alias', ['EW', 'PA'])
            ->get()
            ->keyBy('alias');

        // Validasi ketersediaan
        if (!isset($departments['EW']) || !isset($departments['PA'])) {
            $this->command->error('Pastikan DepartmentSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $divisions = [
            'EW' => [
                ['name' => 'Musik'],
                ['name' => 'Choir'],
                ['name' => 'Vocal'],
                ['name' => 'Multimedia', 'alias' => 'Mulmed'],
                ['name' => 'Sound System'],
            ],
            'PA' => [
                ['name' => 'LICC Profesional', 'alias' => 'Pro'],
                ['name' => 'LICC Youth', 'alias' => 'Youth'],
                ['name' => 'LICC Nextgen', 'alias' => 'NG'],
                [
                    'name' => 'LICC Starkids',
                    'alias' => 'Starkids',
                    'content' => 'Divisi dari Departemen Pemuda dan Anak, yang terkhusus untuk anak TK/SD'
                ],
            ],
        ];

        foreach ($divisions as $deptAlias => $items) {
            $departmentId = $departments[$deptAlias]->id;

            foreach ($items as $item) {
                Division::create(array_merge($item, [
                    'department_id' => $departmentId,
                    'status' => 'active',
                ]));
            }
        }

        $this->command->info('Divisions seeded successfully.');
    }
}
