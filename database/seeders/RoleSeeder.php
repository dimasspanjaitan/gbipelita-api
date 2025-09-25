<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'developer']);
        Role::create(['name' => 'staff']);
        Role::create(['name' => 'leader']);
        Role::create(['name' => 'coreteam']);
        Role::create(['name' => 'volunteer']);
        Role::create(['name' => 'congregation']);
        Role::create(['name' => 'guest']);
    }
}
