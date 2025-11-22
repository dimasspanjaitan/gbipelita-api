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
            $permissions = Permission::pluck('name')->toArray();

            $roles = [
                'Developer',
                'Admin',
                'Youth Pastor',
                'Department Head',
                'Division Leader',
                'Core Team',
                'Volunteer',
                'Congregation',
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
            $developerRole = Role::where('name', 'Developer')->first();
            $adminRole = Role::where('name', 'Admin')->first();

            if ($developerRole) {
                $developerRole->givePermissionTo($permissions);
            }

            if ($adminRole) {
                $adminRole->givePermissionTo($permissions);
            }

            // Assign specific permissions ke role lainnya
            $this->assignRolePermissions('Youth Pastor', [
                'read-user',
                'read-schedule',
            ]);

            $this->assignRolePermissions('Department Head', [
                'read-user',
                'read-role',
                'create-role',
                'update-role',
                'read-schedule',
                'read-assign-schedule',
            ]);

            $this->assignRolePermissions('Division Leader', [
                'read-user',
                'read-schedule',
                'read-assign-schedule',
            ]);

            $this->assignRolePermissions('Core Team', [
                'read-schedule',
                'read-availability-schedule',
            ]);

            $this->assignRolePermissions('Volunteer', [
                'read-schedule',
                'read-availability-schedule',
            ]);
        });
    }

    private function assignRolePermissions(string $roleName, array $permissions)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $role->syncPermissions($permissions);
        }
    }
}