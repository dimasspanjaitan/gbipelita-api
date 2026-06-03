<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use Illuminate\Support\Str;

class ModulesSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'Dashboard',
            'User',
            'Role',
            'Permission',
            'Module',
            'Action',
            'Department',
            'Division',
            'Skill',
            'Volunteer',
            'User Position',
            'Schedule Period',
            'Schedule',
            'Availability Schedule',
            'Profile',
            'Setting'
        ];

        foreach ($modules as $name) {
            Module::firstOrCreate([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
