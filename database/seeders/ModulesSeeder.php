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
            $moduleSlug = Str::slug($moduleName);
            
            Module::updateOrCreate(
                ['slug' => $moduleSlug],
                [
                    'name' => $moduleName,
                    'description' => ucfirst(str_replace('_', ' ', $moduleName)),
                    'order' => $orders[$moduleSlug] ?? $orders[$moduleName] ?? 0,
                ]
            );
        }

        // Update module_id pada permissions_metas
        foreach ($modules as $moduleName) {
            $module = Module::where('slug', Str::slug($moduleName))->first();

            if ($module) {
                PermissionsMeta::where('module', $moduleName)
                    ->update(['module_id' => $module->id]);
            }
        }
    }
}