<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        dd(Course::where('is_published', 1)->get()->count());
        $randomCourses = Course::where('is_published', 1)->get()->random(5);
        return view('main.index', compact('randomCourses'));
    }
}
