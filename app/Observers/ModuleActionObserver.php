<?php

namespace App\Observers;

use App\Models\ModuleAction;
use App\Models\PermissionsMeta;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleActionObserver
{
    /**
     * Handle the ModuleAction "created" event.
     */
    public function created(ModuleAction $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = "{$action->name}-{$action->module->name}";

            // Buat permission baru (atau ambil kalau sudah ada)
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'api',
            ]);

            // Buat metadata permission
            PermissionsMeta::updateOrCreate(
                ['permission_name' => $permissionName],
                [
                    'module_id' => $action->module_id,
                    'module' => $action->module->name,
                    'menu' => Str::headline($action->module->name),
                    'route_name' => "{$action->module->name}.{$action->name}",
                    'action' => $action->name,
                    'description' => "Allow user to {$action->label} in {$action->module->name} module",
                ]
            );
        });
    }

    /**
     * Handle the ModuleAction "updated" event.
     */
    public function updated(ModuleAction $action): void
    {
        DB::transaction(function () use ($action) {
            $newPermissionName = "{$action->name}-{$action->module->name}";

            $oldPermissionName = $action->getOriginal('permission_name') ?? $newPermissionName;

            // Update permission name jika berubah
            $permission = Permission::where('name', $oldPermissionName)->first();
            if ($permission) {
                $permission->update(['name' => $newPermissionName]);
            } else {
                Permission::firstOrCreate([
                    'name' => $newPermissionName,
                    'guard_name' => 'api',
                ]);
            }

            // Update metadata permission
            PermissionsMeta::updateOrCreate(
                ['permission_name' => $newPermissionName],
                [
                    'module_id' => $action->module_id,
                    'module' => $action->module->name,
                    'menu' => Str::headline($action->module->name),
                    'route_name' => "{$action->module->name}.{$action->name}",
                    'action' => $action->name,
                    'description' => "Allow user to {$action->label} in {$action->module->name} module",
                ]
            );
        });
    }

    /**
     * Handle the ModuleAction "deleted" event.
     */
    public function deleted(ModuleAction $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = "{$action->name}-{$action->module->name}";

            Permission::where('name', $permissionName)->delete();
            PermissionsMeta::where('permission_name', $permissionName)->delete();
        });
    }

    /**
     * Handle the ModuleAction "restored" event.
     */
    public function restored(ModuleAction $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = "{$action->name}-{$action->module->name}";

            // Restore permission (buat ulang jika sudah dihapus permanen)
            Permission::withTrashed()
                ->where('name', $permissionName)
                ->restore();

            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'api',
                ]);
            }

            // Restore metadata
            PermissionsMeta::withTrashed()
                ->where('permission_name', $permissionName)
                ->restore();

            if (!PermissionsMeta::where('permission_name', $permissionName)->exists()) {
                PermissionsMeta::create([
                    'module_id' => $action->module_id,
                    'module' => $action->module->name,
                    'menu' => Str::headline($action->module->name),
                    'route_name' => "{$action->module->name}.{$action->name}",
                    'permission_name' => $permissionName,
                    'action' => $action->name,
                    'description' => "Allow user to {$action->label} in {$action->module->name} module",
                ]);
            }
        });
    }

    /**
     * Handle the ModuleAction "forceDeleted" event.
     */
    public function forceDeleted(ModuleAction $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = "{$action->name}-{$action->module->name}";

            Permission::where('name', $permissionName)->forceDelete();
            PermissionsMeta::where('permission_name', $permissionName)->forceDelete();
        });
    }
}
