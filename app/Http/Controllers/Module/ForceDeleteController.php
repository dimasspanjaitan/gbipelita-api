<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        DB::beginTransaction();

        try {
            $module = Module::onlyTrashed()->findOrFail($id);

            // Force delete permission yang terkait
            Permission::where('guard_name', 'api')
                ->where('name', 'LIKE', "%-{$module->slug}")
                ->forceDelete();

            // Detach relations
            $module->actions()->detach();

            // Force delete module
            $module->forceDelete();

            DB::commit();

            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}