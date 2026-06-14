<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $divisions = Division::query()
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('alias', 'like', $search);
                });
            })
            ->when($request->department_id, function ($query) use ($request) {
                $departmentIds = is_array($request->department_id)
                    ? $request->department_id
                    : [$request->department_id];
                $query->whereIn('department_id', $departmentIds);
            })
            ->orderByDesc('created_at')
            ->paginate($request->limit ?? 10)
            ->through(fn($division) => [
                'id' => $division->id,
                'name' => $division->name,
                'alias' => $division->alias,
            ]);

        return response()->json($divisions);
    }
}
