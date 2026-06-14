<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LookupController extends Controller
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
            ->when($request->skill, function ($query) use ($request) {
                $query->whereHas('skills', function ($q) use ($request) {
                    $q->where('name', $request->skill);
                });
            })
            ->when($request->exclude_ids, function ($query) use ($request) {
                $query->whereNotIn('id', $request->exclude_ids);
            })
            ->orderByDesc('created_at')
            ->paginate($request->limit ?? 10)
            ->through(fn($volunteer) => [
                'id' => $volunteer->id,
                'nickname' => $volunteer->nickname,
                'full_name' => $volunteer->full_name,
                'photo' => $volunteer->photo,
            ]);

        return response()->json($volunteers);
    }
}
