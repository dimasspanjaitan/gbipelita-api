<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\PermissionsMeta;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class SyncPermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $modules = Module::with('actions')->get();

            foreach ($modules as $module) {
                foreach ($module->actions as $action) {
                    PermissionsMeta::updateOrCreate(
                        ['permission_name' => $action->permission_name],
                        [
                            'module_id'      => $module->id,
                            'module'         => $module->name,
                            'menu'           => $module->label,
                            'route_name'     => "{$module->name}.{$action->name}",
                            'action'         => $action->name,
                            'description'    => "Allow user to {$action->label} in {$module->label}",
                        ]
                    );

                    Permission::firstOrCreate([
                        'name'        => $action->permission_name,
                        'guard_name'  => 'api',
                    ]);
                }
            }
        });
    }
}
