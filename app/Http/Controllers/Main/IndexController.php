<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $allCourses = Course::where('is_published', 1)->get();
        $countCourses = $allCourses->count();

        if($countCourses >= 6){
            $randomCourses = Course::where('is_published', 1)->get()->random(5);
        }else{
            $randomCourses = $allCourses;
        }

        return view('main.index', compact('randomCourses'));
    }
}
