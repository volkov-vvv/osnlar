<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $randomCourses = Course::where('is_published', 1)->get()->random(6);
        return view('main.index', compact('randomCourses'));
    }
}
