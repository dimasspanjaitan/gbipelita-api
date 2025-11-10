<?php

namespace App\Observers;

use App\Models\Module;
use App\Models\Action;
use App\Models\PermissionsMeta;
use App\Models\Permission; // Ganti dari Spatie
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ModuleObserver
{
    /**
     * Ketika module baru dibuat.
     */
    public function created(Module $module): void
    {
        DB::transaction(function () use ($module) {
            // Forget cached permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

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

                // Buat Action dengan UUID
                Action::firstOrCreate(
                    [
                        'module_id' => $module->id,
                        'name' => $action['name'],
                    ],
                    [
                        'id' => Uuid::uuid4()->toString(),
                        'label' => $action['label'],
                        'permission_name' => $permissionName,
                        'order' => $index,
                        'is_default_action' => true,
                    ]
                );

                // Buat Permission dengan UUID
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'api',
                ], [
                    'id' => Uuid::uuid4()->toString(),
                ]);

                // Sinkron ke PermissionsMeta
                PermissionsMeta::updateOrCreate(
                    ['permission_name' => $permissionName],
                    [
                        'module_id' => $module->id,
                        'module' => $module->name,
                        'menu' => $module->label ?? $module->name,
                        'route_name' => "{$module->name}.{$action['name']}",
                        'action' => $action['name'],
                        'description' => "{$action['label']} data {$module->label} {$module->name}",
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
        // Jika nama module berubah, update semua permissions dan actions
        if ($module->isDirty('name')) {
            DB::transaction(function () use ($module) {
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                $oldModuleName = $module->getOriginal('name');
                $actions = Action::where('module_id', $module->id)->get();

                foreach ($actions as $action) {
                    $oldPermissionName = "{$action->name}-{$oldModuleName}";
                    $newPermissionName = "{$action->name}-{$module->name}";

                    // Update Permission
                    $permission = Permission::where('name', $oldPermissionName)->first();
                    if ($permission) {
                        $permission->update(['name' => $newPermissionName]);
                    } else {
                        // Buat baru jika tidak ditemukan
                        Permission::create([
                            'id' => Uuid::uuid4()->toString(),
                            'name' => $newPermissionName,
                            'guard_name' => 'api',
                        ]);
                    }

                    // Update Action
                    $action->update(['permission_name' => $newPermissionName]);

                    // Update PermissionsMeta
                    PermissionsMeta::where('permission_name', $oldPermissionName)
                        ->update([
                            'permission_name' => $newPermissionName,
                            'module' => $module->name,
                            'menu' => $module->label ?? $module->name,
                            'route_name' => "{$module->name}.{$action->name}",
                        ]);
                }
            });
        }

        // Jika label berubah, update menu di permissions_meta
        if ($module->isDirty('label')) {
            PermissionsMeta::where('module_id', $module->id)
                ->update(['menu' => $module->label ?? $module->name]);
        }
    }

    /**
     * Ketika module dihapus.
     */
    public function deleted(Module $module): void
    {
        DB::transaction(function () use ($module) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $actions = Action::where('module_id', $module->id)->get();

            foreach ($actions as $action) {
                // Soft delete permission
                Permission::where('name', $action->permission_name)->delete();

                // Soft delete metadata
                PermissionsMeta::where('permission_name', $action->permission_name)->delete();

                // Soft delete action
                $action->delete();
            }
        });
    }

    /**
     * Ketika module di-restore.
     */
    public function restored(Module $module): void
    {
        DB::transaction(function () use ($module) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $actions = Action::withTrashed()->where('module_id', $module->id)->get();

            foreach ($actions as $action) {
                // Restore action
                $action->restore();

                // Restore permission
                Permission::withTrashed()
                    ->where('name', $action->permission_name)
                    ->restore();

                // Restore metadata
                PermissionsMeta::withTrashed()
                    ->where('permission_name', $action->permission_name)
                    ->restore();
            }
        });
    }

    /**
     * Ketika module dihapus permanen.
     */
    public function forceDeleted(Module $module): void
    {
        DB::transaction(function () use ($module) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $actions = Action::withTrashed()->where('module_id', $module->id)->get();

            foreach ($actions as $action) {
                // Force delete permission
                Permission::where('name', $action->permission_name)->forceDelete();

                // Force delete metadata
                PermissionsMeta::where('permission_name', $action->permission_name)->forceDelete();

                // Force delete action
                $action->forceDelete();
            }
        });
    }
}