<?php

namespace App\Http\Controllers\CC\Lid;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Status;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Lid $lid)
    {
        $statuses = Status::all();
        $courses = Course::all();
        return view('cc.lid.show', compact('lid','courses','statuses'));
    }
}
