<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\StoreRequest;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $module = Module::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            ]);

            // Sync actions
            $module->actions()->sync($validated['actions']);

            // Create permissions
            foreach ($module->actions as $action) {
                Permission::create([
                    'name' => "$action->name-$module->slug",
                    'guard_name' => 'api'
                ]);
            }

            DB::commit();
            return response()->json($module->load('actions'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
