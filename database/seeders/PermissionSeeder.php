<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use App\Models\Module;
use App\Models\Action;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus cache permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = Module::all();
        $actions = Action::all();

        $now = now();
        $permissionsToCreate = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = "{$action->name}-{$module->slug}";
                $permissionsToCreate[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $permissionName,
                    'guard_name' => 'api',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Insert massal dengan ignore duplicates
        foreach (array_chunk($permissionsToCreate, 100) as $chunk) {
            DB::table('permissions')->insertOrIgnore($chunk);
        }

        DB::table('permissions')->insertOrIgnore([
            'id' => Str::uuid()->toString(),
            'name' => "generate-schedule",
            'guard_name' => 'api',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('permissions')->insertOrIgnore([
            'id' => Str::uuid()->toString(),
            'name' => "open-schedule-period",
            'guard_name' => 'api',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
