<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\PermissionsMeta;
use Illuminate\Support\Str;

class ModulesSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'User',
            'Role',
            'Permission',
            'Module',
            'Action',
            'Department',
            'Division',
            'Schedule',
            'Availability Schedule',
            'Assign Schedule',
        ];

        foreach ($modules as $name) {
            Module::firstOrCreate([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
