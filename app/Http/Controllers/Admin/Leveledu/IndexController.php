<?php

namespace App\Http\Controllers\Admin\Leveledu;

use App\Http\Controllers\Controller;
use App\Models\Leveledu;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $levelsedu = Leveledu::all();
        return view('admin.leveledu.index', compact('levelsedu'));
    }
}
