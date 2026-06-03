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
            ActionSeeder::class,
            ModulesSeeder::class,
            ModuleActionSeeder::class,
            PermissionSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            DivisionSeeder::class,
            SkillSeeder::class,
            UserDepartmentSeeder::class,
            UserDivisionSeeder::class,
            UserSkillSeeder::class,
            UserPositionSeeder::class,
            ScheduleSeeder::class,
            ServiceRequirementSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
