<?php

namespace App\Observers;

use App\Models\Action;
use App\Models\PermissionsMeta;
use App\Models\Permission; // Ganti dari Spatie
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ActionObserver
{
    /**
     * Handle the Action "creating" event.
     */
    public function creating(Action $action): void
    {
        // Generate permission_name jika belum ada
        if (empty($action->permission_name) && $action->module) {
            $action->permission_name = "{$action->name}-{$action->module->name}";
        }
    }

    /**
     * Handle the Action "created" event.
     */
    public function created(Action $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = $action->permission_name;

            // Buat permission baru (atau ambil kalau sudah ada) dengan UUID
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'api',
            ], [
                'id' => Uuid::uuid4()->toString(), // Explicitly set UUID
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
     * Handle the Action "updating" event.
     */
    public function updating(Action $action): void
    {
        // Update permission_name jika module atau name berubah
        if ($action->isDirty(['name', 'module_id']) && $action->module) {
            $action->permission_name = "{$action->name}-{$action->module->name}";
        }
    }

    /**
     * Handle the Action "updated" event.
     */
    public function updated(Action $action): void
    {
        DB::transaction(function () use ($action) {
            $newPermissionName = $action->permission_name;
            $oldPermissionName = $action->getOriginal('permission_name');

            // Jika nama permission berubah, update semua relasi
            if ($oldPermissionName && $oldPermissionName !== $newPermissionName) {
                // Update permission name
                $permission = Permission::where('name', $oldPermissionName)->first();
                if ($permission) {
                    $permission->update(['name' => $newPermissionName]);
                }

                // Update permissions_meta
                PermissionsMeta::where('permission_name', $oldPermissionName)
                    ->update(['permission_name' => $newPermissionName]);
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
     * Handle the Action "deleted" event.
     */
    public function deleted(Action $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = $action->permission_name;

            // Soft delete permission dan metadata
            Permission::where('name', $permissionName)->delete();
            PermissionsMeta::where('permission_name', $permissionName)->delete();
        });
    }

    /**
     * Handle the Action "restored" event.
     */
    public function restored(Action $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = $action->permission_name;

            // Restore permission
            Permission::withTrashed()
                ->where('name', $permissionName)
                ->restore();

            // Jika permission tidak ada (mungkin sudah di force delete), buat baru
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create([
                    'id' => Uuid::uuid4()->toString(),
                    'name' => $permissionName,
                    'guard_name' => 'api',
                ]);
            }

            // Restore metadata
            PermissionsMeta::withTrashed()
                ->where('permission_name', $permissionName)
                ->restore();

            // Jika metadata tidak ada, buat baru
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
     * Handle the Action "forceDeleted" event.
     */
    public function forceDeleted(Action $action): void
    {
        DB::transaction(function () use ($action) {
            $permissionName = $action->permission_name;

            // Hapus permanen permission dan metadata
            Permission::where('name', $permissionName)->forceDelete();
            PermissionsMeta::where('permission_name', $permissionName)->forceDelete();
        });
    }
}