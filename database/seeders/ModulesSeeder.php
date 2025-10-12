<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\PermissionsMeta;
use Illuminate\Support\Str;

class ModulesSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            'master' => 1,
            'schedule' => 2,
            'authorization' => 99,
        ];

        $modules = PermissionsMeta::select('module')
            ->distinct()
            ->pluck('module');

        foreach ($modules as $moduleName) {
            Module::updateOrCreate(
                ['slug' => Str::slug($moduleName)],
                [
                    'name' => $moduleName,
                    'description' => ucfirst(str_replace('_', ' ', $moduleName)),
                    'order' => $orders[$moduleName] ?? 0,
                ]
            );
        }

        // Update module_id pada permissions_metas
        foreach ($modules as $moduleName) {
            $module = Module::where('slug', Str::slug($moduleName))->first();

            PermissionsMeta::where('module', $moduleName)
                ->update(['module_id' => $module->id]);
        }
    }
}
