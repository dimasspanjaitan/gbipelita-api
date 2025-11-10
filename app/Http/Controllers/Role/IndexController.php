<?php

namespace App\Http\Controllers\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $roles = Role::query()
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search);
                });
            })
            ->when($request->sort_column, function ($query) use ($request) {
                $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc');
            })
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        return response()->json($roles);
    }
}