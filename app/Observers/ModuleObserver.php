<?php

namespace App\Observers;

use App\Models\Module;
use App\Models\Action;
use App\Models\PermissionsMeta;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ModuleObserver
{
    /**
     * Ketika module baru dibuat.
     */
    public function created(Module $module): void
    {
        DB::transaction(function () use ($module) {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $defaultActions = [
                ['name' => 'view', 'label' => 'View'],
                ['name' => 'create', 'label' => 'Create'],
                ['name' => 'update', 'label' => 'Update'],
                ['name' => 'delete', 'label' => 'Delete'],
                ['name' => 'restore', 'label' => 'Restore'],
                ['name' => 'force-delete', 'label' => 'Force Delete'],
            ];

            foreach ($defaultActions as $index => $action) {
                $permissionName = "{$action['name']}-{$module->name}";

                // Buat Action
                Action::firstOrCreate(
                    [
                        'module_id' => $module->id,
                        'name' => $action['name'],
                    ],
                    [
                        'label' => $action['label'],
                        'permission_name' => $permissionName,
                        'order' => $index,
                        'is_default_action' => true,
                    ]
                );

                // Buat Permission
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'api',
                ]);

                // Sinkron ke PermissionsMeta
                PermissionsMeta::updateOrCreate(
                    ['permission_name' => $permissionName],
                    [
                        'module_id' => $module->id,
                        'module' => $module->name,
                        'menu' => $module->label,
                        'route_name' => "{$module->name}.{$action['name']}",
                        'action' => $action['name'],
                        'description' => "{$action['label']} data {$module->label}",
                    ]
                );
            }
        });
    }

    /**
     * Ketika module diubah.
     */
    public function updated(Module $module): void
    {
        DB::transaction(function () use ($module) {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $actions = Action::where('module_id', $module->id)->get();

            foreach ($actions as $action) {
                $newPermissionName = "{$action->name}-{$module->name}";

                // Jika nama permission lama berbeda, update semua relasi
                if ($action->permission_name !== $newPermissionName) {
                    // Update Permission di Spatie
                    $permission = Permission::where('name', $action->permission_name)->first();
                    if ($permission) {
                        $permission->update(['name' => $newPermissionName]);
                    }

                    // Update Action & Meta
                    $action->update(['permission_name' => $newPermissionName]);

                    PermissionsMeta::where('permission_name', $action->permission_name)
                        ->update([
                            'permission_name' => $newPermissionName,
                            'module' => $module->name,
                            'menu' => $module->label,
                            'route_name' => "{$module->name}.{$action->name}",
                        ]);
                }
            }
        });
    }

    /**
     * Ketika module dihapus.
     */
    public function deleted(Module $module): void
    {
        DB::transaction(function () use ($module) {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $actions = Action::where('module_id', $module->id)->get();

            foreach ($actions as $action) {
                // Hapus permission dari Spatie
                Permission::where('name', $action->permission_name)->delete();

                // Hapus metadata
                PermissionsMeta::where('permission_name', $action->permission_name)->delete();

                // Hapus module action
                $action->delete();
            }
        });
    }
}
