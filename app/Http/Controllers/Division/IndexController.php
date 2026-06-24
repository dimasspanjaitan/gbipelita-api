<?php

namespace App\Http\Controllers\Division;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
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
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->department_id, function ($query) use ($request) {
                $departmentIds = is_array($request->department_id)
                    ? $request->department_id
                    : [$request->department_id];
                $query->whereIn('department_id', $departmentIds);
            })
            ->when($request->sort_column, function ($query) use ($request) {
                $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc');
            })
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        $divisions->getCollection()->load("department");

        return response()->json(ApiResponse::paginate($divisions));
    }
}
