<?php

namespace App\Http\Controllers\Admin\About;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $abouts = About::all();
        return view('admin.about.index', compact('abouts'));
    }
}
