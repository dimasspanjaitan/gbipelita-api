<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $actions = Action::query()
            ->when($request->search, fn($q, $search) =>
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
            )
            ->when($request->sort_column, function ($query) use ($request) {
                $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc');
            })
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        return response()->json($actions);
    }
}
