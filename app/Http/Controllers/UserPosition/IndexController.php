<?php

namespace App\Http\Controllers\UserPosition;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPosition\IndexRequest;
use App\Models\UserPosition;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $userPositions = UserPosition::query()
            ->when($request->sort_column, function ($query) use ($request) {
                $query->orderBy($request->sort_column, $request->sort_direction ?? 'asc');
            })
            ->when($request->trashed, fn($query) => $query->onlyTrashed())
            ->paginate($request->limit ?? 10);

        $userPositions->getCollection()->load("user", "role", "department", "division");

        return response()->json(ApiResponse::paginate($userPositions));
    }
}
