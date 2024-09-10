<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\LidsTestExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportTestController extends Controller
{
    public function __invoke()
    {
        $param = array();
        $columnSortName = '';
        $columnSortOrder = '';
        return (new LidsTestExport)
            ->Params($param)
            ->Order($columnSortName, $columnSortOrder)
            ->download('lids-test.xlsx');
    }
}
