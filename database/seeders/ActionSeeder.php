<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\Action;
use App\Models\Permission;

class ActionSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Hapus cache permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            $defaultActions = [
                ['name' => 'view', 'label' => 'View'],
                ['name' => 'create', 'label' => 'Create'],
                ['name' => 'update', 'label' => 'Update'],
                ['name' => 'delete', 'label' => 'Delete'],
                ['name' => 'restore', 'label' => 'Restore'],
                ['name' => 'force-delete', 'label' => 'Force Delete'],
            ];

            $modules = Module::all();

            foreach ($modules as $module) {
                foreach ($defaultActions as $index => $action) {
                    $permissionName = "{$action['name']}-{$module->name}";

                    // Buat action
                    $moduleAction = Action::firstOrCreate(
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

                    // Buat permission
                    Permission::firstOrCreate([
                        'name' => $moduleAction->permission_name,
                        'guard_name' => 'api',
                    ]);
                }
            }
        });
    }
}