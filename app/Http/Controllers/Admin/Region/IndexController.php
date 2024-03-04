<?php

namespace App\Http\Controllers\Admin\Region;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $regions = Region::all();
        return view('admin.region.index', compact('regions'));
    }
}
