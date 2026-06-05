<?php

namespace App\Http\Controllers\ScheduleAvailability;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleAvailability\IndexRequest;
use App\Models\SchedulePeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $schedulePeriodOpen = SchedulePeriod::query()
            ->with('department')
            ->where('status', 'open')
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

        return response()->json($schedulePeriodOpen);
    }
}
