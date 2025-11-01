<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Illuminate\Http\Resources\Json\JsonResource;

class RestoreController extends Controller
{
    public function __invoke(string $id)
    {
        $action = Action::withTrashed()->find($id);

        if (!$action) abort(404);

        try {
            $action->restore();
            return new JsonResource($action);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
