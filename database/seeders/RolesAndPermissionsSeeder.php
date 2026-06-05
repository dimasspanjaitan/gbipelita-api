<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role; // Ganti dari Spatie
use App\Models\Permission; // Ganti dari Spatie

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus cache permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            $permissions = Permission::query()->pluck('name')->toArray();

            $roles = [
                'Developer',
                'Admin',
                // 'Youth Pastor',
                'Department Head',
                'Division Leader',
                // 'Core Team',
                'Volunteer',
                // 'Congregation',
                'Guest',
            ];

            // Buat roles
            foreach ($roles as $roleName) {
                Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'api',
                ]);
            }

            // Assign permissions ke roles
            $developerRole = Role::query()->where('name', 'Developer')->first();
            $adminRole = Role::query()->where('name', 'Admin')->first();

            if ($developerRole) {
                $developerRole->givePermissionTo($permissions);
            }

            if ($adminRole) {
                $adminRole->givePermissionTo($permissions);
            }

            // Assign specific permissions ke role lainnya
            // $this->assignRolePermissions('Youth Pastor', [
            //     'menu-dashboard',
            //     'read-dashboard',
            //     'menu-volunteer',
            //     'read-volunteer',
            //     'menu-schedule',
            //     'read-schedule',
            // ]);

            $this->assignRolePermissions('Department Head', [
                'menu-dashboard',
                'read-dashboard',
                'menu-user',
                'read-user',
                'show-user',
                'create-user',
                'update-user',
                'delete-user',
                'restore-user',
                'force-delete-user',
                'menu-department',
                'read-department',
                'show-department',
                'create-department',
                'update-department',
                'delete-department',
                'restore-department',
                'force-delete-department',
                'menu-division',
                'read-division',
                'show-division',
                'create-division',
                'update-division',
                'delete-division',
                'restore-division',
                'force-delete-division',
                'menu-skill',
                'read-skill',
                'show-skill',
                'create-skill',
                'update-skill',
                'delete-skill',
                'restore-skill',
                'force-delete-skill',
                'menu-volunteer',
                'read-volunteer',
                'show-volunteer',
                'create-volunteer',
                'update-volunteer',
                'delete-volunteer',
                'restore-volunteer',
                'force-delete-volunteer',
                'read-role',
                'menu-schedule-period',
                'read-schedule-period',
                'show-schedule-period',
                'create-schedule-period',
                'update-schedule-period',
                'delete-schedule-period',
                'restore-schedule-period',
                'force-delete-schedule-period',
                'menu-schedule',
                'read-schedule',
                'show-schedule',
                'create-schedule',
                'update-schedule',
                'menu-availability-schedule',
                'read-availability-schedule',
                'show-availability-schedule',
                'create-availability-schedule',
                'update-availability-schedule',
                'generate-schedule'
            ]);

            $this->assignRolePermissions('Division Leader', [
                'menu-volunteer',
                'read-volunteer',
                'show-volunteer',
                'menu-schedule',
                'read-schedule',
                'menu-schedule-period',
                'read-schedule-period',
                'show-schedule-period',
                'update-schedule',
            ]);

            // $this->assignRolePermissions('Core Team', [
            //     'menu-schedule',
            //     'read-schedule',
            //     'menu-availability-schedule',
            //     'read-availability-schedule',
            // ]);

            $this->assignRolePermissions('Volunteer', [
                'menu-schedule',
                'show-schedule',
                'read-schedule',
                'menu-availability-schedule',
                'read-availability-schedule',
                'show-availability-schedule',
                'create-availability-schedule',
                'update-availability-schedule',
                'read-profile',
                'show-profile',
                'update-profile',
            ]);
        });
    }

    private function assignRolePermissions(string $roleName, array $permissions)
    {
        $role = Role::query()->where('name', $roleName)->first();
        if ($role) {
            $role->syncPermissions($permissions);
        }
    }
}