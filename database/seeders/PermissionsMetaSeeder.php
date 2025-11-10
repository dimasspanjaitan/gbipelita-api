<?php

namespace Database\Seeders;

use App\Models\PermissionsMeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Permission;

class PermissionsMetaSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard' => ['Dashboard', ['view']],
            'user' => ['User', ['view', 'show', 'create', 'update', 'delete', 'restore', 'force-delete']],
            'action' => ['Action', ['view', 'show', 'create', 'update', 'delete', 'restore', 'force-delete']],
            'module' => ['Module', ['view', 'show', 'create', 'update', 'delete', 'restore', 'force-delete']],
            'role' => ['Role', ['view', 'show', 'create', 'update', 'delete', 'restore', 'force-delete']],
            'department' => ['Department', ['view', 'show', 'create', 'update', 'delete', 'restore', 'force-delete']],
            'division' => ['Division', ['view', 'show', 'create', 'update', 'delete', 'restore', 'force-delete']],
            'schedule' => [
                'Jadwal',
                [
                    ['menu' => 'Lihat Jadwal', 'route' => 'schedules.view', 'action' => 'view'],
                    ['menu' => 'Isi Ketersediaan', 'route' => 'schedule.availability', 'action' => 'availability'],
                    ['menu' => 'Tugaskan Jadwal', 'route' => 'schedule.assign', 'action' => 'assign'],
                ]
            ],
        ];

        foreach ($permissions as $module => $data) {
            [$menu, $actions] = $data;

            foreach ($actions as $item) {
                if (is_string($item)) {
                    $action = $item;
                    $route = "{$module}s.{$action}";
                    $menuName = $menu;
                } else {
                    $action = $item['action'];
                    $route = $item['route'];
                    $menuName = $item['menu'];
                }

                $permissionName = "{$action}-{$module}";

                // Simpan ke permissions_meta
                PermissionsMeta::updateOrCreate(
                    ['permission_name' => $permissionName],
                    [
                        'module' => $module,
                        'menu' => $menuName,
                        'route_name' => $route,
                        'permission_name' => $permissionName,
                        'action' => $action,
                        'description' => Str::title(str_replace('-', ' ', $permissionName)),
                    ]
                );

                // Sinkronkan dengan permissions (model custom)
                Permission::updateOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'api'],
                    ['name' => $permissionName, 'guard_name' => 'api']
                );
            }
        }
    }
}