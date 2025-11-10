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
                'developer',
                'admin',
                'pastor_youth',
                'pastor_ic',
                'department_head',
                'division_leader',
                'core_team',
                'volunteer',
                'congregation',
                'guest',
            ];

            // Buat roles
            foreach ($roles as $roleName) {
                Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'api',
                ]);
            }

            // Assign permissions ke roles
            $developerRole = Role::where('name', 'developer')->first();
            $adminRole = Role::where('name', 'admin')->first();

            if ($developerRole) {
                $developerRole->givePermissionTo($permissions);
            }

            if ($adminRole) {
                $adminRole->givePermissionTo($permissions);
            }

            // Assign specific permissions ke role lainnya
            $this->assignRolePermissions('pastor_youth', [
                'view-user',
                'view-schedule',
            ]);

            $this->assignRolePermissions('department_head', [
                'view-user',
                'view-role',
                'create-role',
                'update-role',
                'view-schedule',
                'assign-schedule',
            ]);

            $this->assignRolePermissions('division_leader', [
                'view-user',
                'view-schedule',
                'assign-schedule',
            ]);

            $this->assignRolePermissions('core_team', [
                'view-schedule',
                'availability-schedule',
            ]);

            $this->assignRolePermissions('volunteer', [
                'view-schedule',
                'availability-schedule',
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