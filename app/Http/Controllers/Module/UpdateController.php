<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\UpdateRequest;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Module $module)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // Simpan slug lama
            $oldSlug = $module->slug;

            // Update module
            $module->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? $module->slug,
                'order' => $validated['order'],
            ]);

            // Sync actions
            $module->actions()->sync($validated['actions']);
            $module->load('actions');

            // HAPUS SEMUA PERMISSION UNTUK MODULE INI DENGAN RAW QUERY
            $this->deleteModulePermissionsWithRawQuery($oldSlug, $module->slug);

            // BUAT PERMISSION BARU DENGAN RAW QUERY UNTUK MENGHINDARI MODEL EVENT
            $this->createPermissionsWithRawQuery($module->actions, $module->slug);

            DB::commit();

            return response()->json($module->load('actions'));

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus permissions dengan raw query untuk menghindari model events
     */
    private function deleteModulePermissionsWithRawQuery(string $oldSlug, string $newSlug): void
    {
        DB::table('permissions')
            ->where('guard_name', 'api')
            ->where(function ($query) use ($oldSlug, $newSlug) {
                $query->where('name', 'LIKE', "%-{$oldSlug}")
                      ->orWhere('name', 'LIKE', "%-{$newSlug}");
            })
            ->delete();
    }

    /**
     * Buat permissions dengan raw query untuk menghindari duplicate check dari package
     */
    private function createPermissionsWithRawQuery($actions, string $slug): void
    {
        $now = now();
        $permissions = [];

        foreach ($actions as $action) {
            $permissionName = "{$action->name}-{$slug}";
            
            $permissions[] = [
                'id' => Str::uuid(),
                'name' => $permissionName,
                'guard_name' => 'api',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($permissions)) {
            // Gunakan INSERT IGNORE untuk menghindari duplicate error
            foreach ($permissions as $permission) {
                DB::table('permissions')->insertOrIgnore($permission);
            }
        }
    }
}