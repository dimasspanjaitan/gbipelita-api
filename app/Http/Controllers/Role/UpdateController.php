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
        $validated = $request->validated();

        try {
            $role = DB::transaction(function () use ($id, $validated) {
                $role = Role::findOrFail($id);
                $role->update(['name' => $validated['name']]);

                if (!empty($validated['permissions'])) {
                    $role->syncPermissions($validated['permissions']);
                }

                return $role->load('permissions');
            });

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui.',
                'data' => $role,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
