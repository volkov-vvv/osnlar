<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Lid $lid)
    {
        $courses = Course::all();
        return view('admin.lid.show', compact('lid','courses'));
    }
}
