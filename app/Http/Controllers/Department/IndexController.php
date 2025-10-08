<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $limit = (int) $request->query('limit', 10);
        $limit = $limit > 0 ? min($limit, 100) : 10;

        $data = Department::query();

        if ($request->query('trashed') === 'only') {
            $data->onlyTrashed();
        } elseif ($request->query('trashed') === 'with') {
            $data->withTrashed();
        }

        $datas = $data->paginate($limit);

        return response()->json($datas);
    }
}
