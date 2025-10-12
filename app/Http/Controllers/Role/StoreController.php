<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $role = DB::transaction(function () use ($validated) {
                $role = Role::create([
                    'name' => $validated['name'],
                    'guard_name' => 'api',
                ]);

                if (!empty($validated['permissions'])) {
                    $role->syncPermissions($validated['permissions']);
                }

                return $role->load('permissions');
            });

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dibuat.',
                'data' => $role,
            ], 201);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
