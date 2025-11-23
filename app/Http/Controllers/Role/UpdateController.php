<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Role $role)
    {
        $validated = $request->validated();

        try {
            $role = DB::transaction(function () use ($role, $validated) {
                $role->update([
                    'name' => $validated['name'],
                ]);

                if (isset($validated['permissions'])) {
                    $role->syncPermissions($validated['permissions']);
                }

                return $role->load('permissions');
            });

            return response()->json($role);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}