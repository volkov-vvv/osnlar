<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $allCourses = Course::where('is_published', 1)->where('company_id', 1)->where('price', NULL)->get();
        $countCourses = $allCourses->count();

        if($countCourses >= 3){
            $randomCourses = Course::where('is_published', 1)->where('company_id', 1)->where('price', NULL)->get()->random(3);
        }else{
            $randomCourses = $allCourses;
        }

        $listenersCount = Lid::query()->count();
        $programsCount = Course::query()->count();

        return view('main.index', compact('randomCourses', 'listenersCount', 'programsCount'));
    }
}
