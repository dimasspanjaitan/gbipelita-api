<?php

namespace App\Http\Controllers\UserPosition;

use App\Http\Controllers\Controller;
use App\Models\UserPosition;

class ShowController extends Controller
{

    public function __invoke(UserPosition $userPosition)
    {
        return response()->json($userPosition->load(['user', 'role', 'department', 'division']));
    }
}
