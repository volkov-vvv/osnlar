<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\LidsExport;

class ExportController extends Controller
{
    public function __invoke()
    {
        $param = array();
        $columnSortName = '';
        $columnSortOrder = '';
        $filename = 'lidsReport.xlsx';
        (new LidsExport)
            ->Params($param)
            ->Order($columnSortName, $columnSortOrder)
            ->queue($filename);
        return back()->withSuccess('Export started!');
    }
}
