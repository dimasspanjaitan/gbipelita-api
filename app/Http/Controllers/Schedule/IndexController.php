<?php

namespace App\Http\Controllers\Schedule;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\SchedulePeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $schedules = SchedulePeriod::query()
            ->with('department')
            ->where('status', 'published')
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('year', 'like', $search);
                });
            })
            ->when($request->sort_column, function ($query) use ($request) {
                $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc');
            })
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        return response()->json(ApiResponse::paginate($schedules));
    }
}
