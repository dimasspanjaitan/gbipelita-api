<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @mixin \Illuminate\Routing\Controller
     */
    public function __construct()
    {
        // Permission untuk melihat daftar department
        $this->middleware('permission:master_departments_view');
    }

    /**
     * Menampilkan daftar semua Department (GET /departments)
     */
    public function __invoke(Request $request)
    {
        $limit = (int) $request->query('limit', 10);
        $limit = $limit > 0 ? min($limit, 100) : 10;

        $queryData = Department::query();

        if ($request->query('trashed') === 'only') {
            $queryData->onlyTrashed();
        } elseif ($request->query('trashed') === 'with') {
            $queryData->withTrashed();
        }

        $datas = $queryData->paginate($limit);

        return response()->json($datas);
    }
}
