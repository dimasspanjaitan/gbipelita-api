<?php

namespace Database\Seeders;

use App\Models\PermissionsMeta;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SyncPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $metas = PermissionsMeta::all();

        foreach ($metas as $meta) {
            Permission::updateOrCreate(
                ['name' => $meta->permission_name],
                ['guard_name' => 'web']
            );
        }
    }
}
