<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\LidsExport;
//use Storage;

class ExportController extends Controller
{
    public function __invoke()
    {
        $param = array();
        $columnSortName = '';
        $columnSortOrder = '';
        $filename = 'reports/lidsReport.xlsx';
        phpinfo();
//        dd('!!!');

        (new LidsExport)
            ->Params($param)
            ->Order($columnSortName, $columnSortOrder)
            ->store($filename);
        //return back()->withSuccess('Export started!');
        return 'OK';

    }
}
