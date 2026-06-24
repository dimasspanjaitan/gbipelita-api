<?php

namespace App\Http\Controllers\Department;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
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
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->when($request->sort_column, fn($query) => $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc'))
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        $departments->getCollection()->load('divisions');

        return response()->json(ApiResponse::paginate($departments));
    }
}
