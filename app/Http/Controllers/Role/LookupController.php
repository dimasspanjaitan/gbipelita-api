<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class LookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $userRole = auth()->user()->getRoleNames()->first();

        $query = Role::query();

        match ($userRole) {
            'Developer' => $query,

            'Admin' => $query->where('name', '!=', 'Developer'),

            'Department Head' => $query->whereIn('name', [
                'Division Leader',
                'Volunteer',
            ]),

            default => null,
        };

        if (! in_array($userRole, ['Developer', 'Admin', 'Department Head'])) {
            return response()->json([]);
        }

        $roles = $query
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";

                $query->where('name', 'like', $search);
            })
            ->when($request->boolean('position'), function ($query) {
                $query->whereIn('name', [
                    'Department Head',
                    'Division Leader',
                ]);
            })
            ->orderByDesc('created_at')
            ->paginate($request->limit ?? 10)
            ->through(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]);

        return response()->json($roles);
    }
}
