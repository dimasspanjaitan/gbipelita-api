<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PermissionsMeta;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            $permissions = [
                'master' => [
                    'Pengguna' => [
                        ['route' => 'users.view', 'action' => 'view', 'permission' => 'view-users'],
                        ['route' => 'users.create', 'action' => 'create', 'permission' => 'create-users'],
                        ['route' => 'users.edit', 'action' => 'edit', 'permission' => 'update-users'],
                        ['route' => 'users.delete', 'action' => 'delete', 'permission' => 'delete-users'],
                        ['route' => 'users.restore', 'action' => 'restore', 'permission' => 'restore-users'],
                        ['route' => 'users.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-users'],
                    ],
                    'Departemen' => [
                        ['route' => 'departments.view', 'action' => 'view', 'permission' => 'view-departments'],
                        ['route' => 'departments.create', 'action' => 'create', 'permission' => 'create-departments'],
                        ['route' => 'departments.edit', 'action' => 'edit', 'permission' => 'update-departments'],
                        ['route' => 'departments.delete', 'action' => 'delete', 'permission' => 'delete-departments'],
                        ['route' => 'departments.restore', 'action' => 'restore', 'permission' => 'restore-departments'],
                        ['route' => 'departments.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-departments'],
                    ],
                ],
                'schedule' => [
                    'Lihat Jadwal' => [
                        ['route' => 'schedules.view', 'action' => 'view', 'permission' => 'view-schedules'],
                    ],
                    'Isi Ketersediaan' => [
                        ['route' => 'schedule.availability', 'action' => 'add', 'permission' => 'schedules-availability'],
                    ],
                    'Tugaskan Jadwal' => [
                        ['route' => 'schedule.assign', 'action' => 'edit', 'permission' => 'schedules-assign-manual'],
                    ],
                ],
                'authorization' => [
                    'Menu' => [
                        ['route' => 'menus.view', 'action' => 'view', 'permission' => 'view-menus'],
                        ['route' => 'menus.create', 'action' => 'create', 'permission' => 'create-menus'],
                        ['route' => 'menus.edit', 'action' => 'edit', 'permission' => 'update-menus'],
                        ['route' => 'menus.delete', 'action' => 'delete', 'permission' => 'delete-menus'],
                        ['route' => 'menus.restore', 'action' => 'restore', 'permission' => 'restore-menus'],
                        ['route' => 'menus.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-menus'],
                    ],
                    'Role' => [
                        ['route' => 'roles.view', 'action' => 'view', 'permission' => 'view-roles'],
                        ['route' => 'roles.create', 'action' => 'create', 'permission' => 'create-roles'],
                        ['route' => 'roles.edit', 'action' => 'edit', 'permission' => 'update-roles'],
                        ['route' => 'roles.delete', 'action' => 'delete', 'permission' => 'delete-roles'],
                        ['route' => 'roles.restore', 'action' => 'restore', 'permission' => 'restore-roles'],
                        ['route' => 'roles.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-roles'],
                    ],
                    'Permission' => [
                        ['route' => 'permissions.view', 'action' => 'view', 'permission' => 'view-permissions'],
                    ],
                ]
            ];

            // 🔹 Buat permission + metadata
            foreach ($permissions as $module => $menus) {
                foreach ($menus as $menu => $items) {
                    foreach ($items as $perm) {
                        Permission::firstOrCreate([
                            'name' => $perm['permission'],
                            'guard_name' => 'api',
                        ]);

                        PermissionsMeta::updateOrCreate(
                            ['permission_name' => $perm['permission']],
                            [
                                'module' => $module,
                                'menu' => $menu,
                                'route_name' => $perm['route'],
                                'action' => $perm['action'],
                            ]
                        );
                    }
                }
            }

            // 🔹 Buat roles
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

            // 🔹 Assign permission ke role
            Role::findByName('developer', 'api')->givePermissionTo(Permission::all());
            Role::findByName('admin', 'api')->givePermissionTo(Permission::all());

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
                'schedules-assign-manual',
            ]);

            Role::findByName('division_leader', 'api')->syncPermissions([
                'view-users',
                'view-schedules',
                'schedules-assign-manual',
            ]);

            Role::findByName('core_team', 'api')->syncPermissions([
                'view-schedules',
                'schedules-availability',
            ]);

            Role::findByName('volunteer', 'api')->syncPermissions([
                'view-schedules',
                'schedules-availability',
            ]);
        });
    }
}
