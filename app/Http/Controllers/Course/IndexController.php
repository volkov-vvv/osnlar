<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $courses = Course::all();
        return view('course.index',compact('courses'));
    }
}
