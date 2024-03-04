<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Models\Lid;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $lids = Lid::all();
        return view('admin.lid.index', compact('lids'));
    }
}
