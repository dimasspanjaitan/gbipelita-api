<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Action;
use Illuminate\Database\Eloquent\Model;

class ActionSeeder extends Seeder
{
    public function run()
    {
        $actions = [
            'menu',
            'read',
            'show',
            'create',
            'update',
            'delete',
            'restore',
            'force-delete',
            'export',
            'import',
        ];

        foreach ($actions as $name) {
            Action::firstOrCreate(['name' => $name]);
        }
    }
}
