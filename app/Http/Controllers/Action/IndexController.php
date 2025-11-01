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
        $query = Action::query()
            ->with('module')
            ->when($request->module_id, fn($q, $moduleId) => $q->where('module_id', $moduleId))
            ->when($request->search, fn($q, $search) =>
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('label', 'like', "%{$search}%")
                        ->orWhere('permission_name', 'like', "%{$search}%");
                })
            )
            ->when($request->trashed, fn($q) => $q->onlyTrashed())
            ->orderBy($request->sort_column ?? 'order', $request->sort_direction ?? 'asc');

        $actions = $query->paginate($request->limit ?? 10);

        return response()->json($actions);
    }
}
