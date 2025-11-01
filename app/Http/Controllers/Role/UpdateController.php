<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $role = Role::query()->findOrFail($id);
            $role->update([
                'name' => $validated['name'],
            ]);

            if(!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            DB::commit();

            return response()->json($role->fresh('permissions'));
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
