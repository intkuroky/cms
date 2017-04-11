<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Filters\LogListFilter;
use App\Models\SystemLog;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class SystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function logs(Request $request, LogListFilter $filter)
    {
        $filters = $request->all();

        $logsCollectionsBuilder = SystemLog::query();
        $logsCollectionsBuilder = $filter->filter($logsCollectionsBuilder, $filters);
        $logsCollections = $logsCollectionsBuilder->paginate(10, [ '*' ], 'logsPage');

        return view('system.logs', compact('logsCollections', 'filters'));
    }

}