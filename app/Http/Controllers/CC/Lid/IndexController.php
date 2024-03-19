<?php

namespace App\Http\Controllers\CC\Lid;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Status;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $statuses = Status::all();
        $courses = Course::all();
        $lids = Lid::all();
        return view('cc.lid.index', compact('lids','courses','statuses'));
    }
}
