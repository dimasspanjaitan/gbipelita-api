<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            ModulesSeeder::class,
            SyncPermissionSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            DivisionSeeder::class,
            UserDepartmentSeeder::class,
            UserDivisionSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
