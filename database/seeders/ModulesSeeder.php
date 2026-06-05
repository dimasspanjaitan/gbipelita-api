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
            // Master Data
            'Department',
            'Division',
            'Skill',
            'Volunteer',
            'User Position',
            // Schedule
            'Schedule Period',
            'Availability Schedule',
            'Schedule',
            // Authorization
            'Action',
            'Module',
            'Role',
            'User',
            // OTher
            'Permission',
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
