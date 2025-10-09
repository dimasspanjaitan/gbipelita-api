<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
                'name' => 'Excellent Worshipper',
                'alias' => 'EW',
                'content' => 'Departemen Pelayan untuk Ibadah Raya Minggu',
                'status' => 'active'
            ]);

        Department::create([
                'name' => 'Pemuda & Anak',
                'alias' => 'PA',
                'content' => 'Departemen Pemuda dan Anak, termasuk Profesional, Youth, Nextgen, Starkids',
                'status' => 'active'
            ]);
    }
}
