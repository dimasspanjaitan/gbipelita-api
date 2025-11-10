<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\PermissionsMeta;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class SyncPermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $modules = Module::with('actions')->get();

            foreach ($modules as $module) {
                foreach ($module->actions as $action) {
                    // Update permissions_meta
                    PermissionsMeta::updateOrCreate(
                        ['permission_name' => $action->permission_name],
                        [
                            'module_id'      => $module->id,
                            'module'         => $module->name,
                            'menu'           => $module->description ?? $module->name,
                            'route_name'     => "{$module->name}.{$action->name}",
                            'action'         => $action->name,
                            'description'    => "Allow user to {$action->label} in {$module->description}",
                        ]
                    );

                    // Pastikan permission ada
                    Permission::firstOrCreate([
                        'name'        => $action->permission_name,
                        'guard_name'  => 'api',
                    ]);
                }
            }
        });
    }
}