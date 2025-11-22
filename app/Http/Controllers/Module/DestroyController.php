<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class DestroyController extends Controller
{
    public function __invoke(Module $module)
    {
        DB::beginTransaction();

        try {
            // Hapus permission yang terkait dengan module ini
            Permission::where('guard_name', 'api')
                ->where('name', 'LIKE', "%-{$module->slug}")
                ->delete();

            // Soft delete module (relasi actions akan tetap ada karena soft delete)
            $module->delete();

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