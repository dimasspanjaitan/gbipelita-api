<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\ModuleAction;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ModuleActionSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

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
                    logger()->info('Generating permissions for module', [$module->name]);
                    $permissionName = "{$action['name']}-{$module->name}";

                    $moduleAction = ModuleAction::firstOrCreate(
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

                    Permission::firstOrCreate([
                        'name' => $moduleAction->permission_name,
                        'guard_name' => 'api',
                    ]);
                }
            }
        });
    }
}
