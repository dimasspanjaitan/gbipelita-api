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
                        ['route' => 'users.view', 'action' => 'view', 'permission' => 'view-user'],
                        ['route' => 'users.create', 'action' => 'create', 'permission' => 'create-user'],
                        ['route' => 'users.edit', 'action' => 'edit', 'permission' => 'update-user'],
                        ['route' => 'users.delete', 'action' => 'delete', 'permission' => 'delete-user'],
                        ['route' => 'users.restore', 'action' => 'restore', 'permission' => 'restore-user'],
                        ['route' => 'users.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-user'],
                    ],
                    'Departemen' => [
                        ['route' => 'departments.view', 'action' => 'view', 'permission' => 'view-department'],
                        ['route' => 'departments.create', 'action' => 'create', 'permission' => 'create-department'],
                        ['route' => 'departments.edit', 'action' => 'edit', 'permission' => 'update-department'],
                        ['route' => 'departments.delete', 'action' => 'delete', 'permission' => 'delete-department'],
                        ['route' => 'departments.restore', 'action' => 'restore', 'permission' => 'restore-department'],
                        ['route' => 'departments.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-department'],
                    ],
                ],
                'schedule' => [
                    'Lihat Jadwal' => [
                        ['route' => 'schedules.view', 'action' => 'view', 'permission' => 'view-schedule'],
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
                        ['route' => 'menus.view', 'action' => 'view', 'permission' => 'view-menu'],
                        ['route' => 'menus.create', 'action' => 'create', 'permission' => 'create-menu'],
                        ['route' => 'menus.edit', 'action' => 'edit', 'permission' => 'update-menu'],
                        ['route' => 'menus.delete', 'action' => 'delete', 'permission' => 'delete-menu'],
                        ['route' => 'menus.restore', 'action' => 'restore', 'permission' => 'restore-menu'],
                        ['route' => 'menus.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-menu'],
                    ],
                    'Role' => [
                        ['route' => 'roles.view', 'action' => 'view', 'permission' => 'view-role'],
                        ['route' => 'roles.create', 'action' => 'create', 'permission' => 'create-role'],
                        ['route' => 'roles.edit', 'action' => 'edit', 'permission' => 'update-role'],
                        ['route' => 'roles.delete', 'action' => 'delete', 'permission' => 'delete-role'],
                        ['route' => 'roles.restore', 'action' => 'restore', 'permission' => 'restore-role'],
                        ['route' => 'roles.force-delete', 'action' => 'force-delete', 'permission' => 'force-delete-role'],
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
                'view-user',
                'view-schedule',
            ]);

            Role::findByName('department_head', 'api')->syncPermissions([
                'view-user',
                'view-role',
                'create-role',
                'update-role',
                'view-schedule',
                'schedules-assign-manual',
            ]);

            Role::findByName('division_leader', 'api')->syncPermissions([
                'view-user',
                'view-schedule',
                'schedules-assign-manual',
            ]);

            Role::findByName('core_team', 'api')->syncPermissions([
                'view-schedule',
                'schedules-availability',
            ]);

            Role::findByName('volunteer', 'api')->syncPermissions([
                'view-schedule',
                'schedules-availability',
            ]);
        });
    }
}
