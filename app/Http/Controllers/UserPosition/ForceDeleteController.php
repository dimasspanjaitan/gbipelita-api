<?php

namespace App\Http\Controllers\UserPosition;

use App\Http\Controllers\Controller;
use App\Models\UserPosition;

class ForceDeleteController extends Controller
{
    public function __invoke(string $id)
    {
        $userPosition = UserPosition::onlyTrashed()->findOrFail($id);
        $userPosition->forceDelete();

        return response()->noContent();
    }
}
