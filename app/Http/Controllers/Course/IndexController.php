<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $courses = Course::where('is_published', 1)->get();
        $pageTitle = "Курсы";
        $pageDescription = "Бесплатные курсы Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»";
        return view('course.index',compact('courses', 'pageTitle', 'pageDescription'));
    }
}
