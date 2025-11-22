<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Action;
use Illuminate\Database\Seeder;

class ModuleActionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = Action::all();

        Module::all()->each(function ($module) use ($actions) {
            $module->actions()->sync($actions->pluck('id'));
        });
    }
}
