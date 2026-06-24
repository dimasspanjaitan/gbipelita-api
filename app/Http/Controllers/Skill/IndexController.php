<?php

namespace App\Http\Controllers\Skill;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $skills = Skill::query()
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search);
                });
            })
            ->when($request->division_id, function ($query) use ($request) {
                $divisionIds = is_array($request->division_id)
                    ? $request->division_id
                    : [$request->division_id];
                $query->whereIn('division_id', $divisionIds);
            })
            ->when($request->sort_column, function ($query) use ($request) {
                $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc');
            })
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        $skills->getCollection()->load("division");

        return response()->json(ApiResponse::paginate($skills));
    }
}
