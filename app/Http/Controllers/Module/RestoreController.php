<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        DB::beginTransaction();

        try {
            $module = Module::onlyTrashed()->findOrFail($id);
            $module->restore();
            
            // Restore permission yang terkait dengan module ini
            Permission::where('guard_name', 'api')
                ->where('name', 'LIKE', "%-{$module->slug}")
                ->restore();

            DB::commit();

            return response()->json($module->load('actions'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}