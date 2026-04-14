<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $volunteers = User::query()
            ->when($request->search, function ($query, $search) {
                $search = "%{$search}%";
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('nickname', 'like', $search)
                        ->orWhere('username', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->whereHas('roles', function ($q) {
                $q->where('name', 'volunteer');
            })
            ->with(['roles', 'departments', 'divisions'])
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->sort_column, function ($query) use ($request) {
                $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc');
            })
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        return response()->json($volunteers);
    }
}
