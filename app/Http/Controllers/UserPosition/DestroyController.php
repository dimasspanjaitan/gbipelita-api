<?php

namespace App\Http\Controllers\UserPosition;

use App\Http\Controllers\Controller;
use App\Models\UserPosition;

class DestroyController extends Controller
{
    public function __invoke(UserPosition $userPosition)
    {
        $userPosition->delete();
        return response()->noContent();
    }
}
