<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

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

            foreach ($roles as $roleName) {
                Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'api',
                ]);
            }

            Role::findByName('developer', 'api')->givePermissionTo($permissions);
            Role::findByName('admin', 'api')->givePermissionTo($permissions);

            Role::findByName('pastor_youth', 'api')->syncPermissions([
                'view-users',
                'view-schedules',
            ]);

            Role::findByName('department_head', 'api')->syncPermissions([
                'view-users',
                'view-roles',
                'create-roles',
                'update-roles',
                'view-schedules',
                'assign-schedules',
            ]);

            Role::findByName('division_leader', 'api')->syncPermissions([
                'view-users',
                'view-schedules',
                'assign-schedules',
            ]);

            Role::findByName('core_team', 'api')->syncPermissions([
                'view-schedules',
                'availability-schedules',
            ]);

            Role::findByName('volunteer', 'api')->syncPermissions([
                'view-schedules',
                'availability-schedules',
            ]);
        });
    }
}
