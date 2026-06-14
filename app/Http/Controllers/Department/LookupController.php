<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $departments = Department::query()
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('alias', 'like', $search);
                });
            })
            ->orderByDesc('created_at')
            ->paginate($request->limit ?? 10)
            ->through(fn($department) => [
                'id' => $department->id,
                'name' => $department->name,
                'alias' => $department->alias,
            ]);

        return response()->json($departments);
    }
}
