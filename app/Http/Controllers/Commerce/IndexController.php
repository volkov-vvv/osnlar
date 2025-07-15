<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $courses = Course::where('is_published', 1)->whereNotNull('price')->get();
        $pageTitle = "Платные курсы";
        $pageDescription = "Платные курсы Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»";
        return view('commerce.index',compact('courses', 'pageTitle', 'pageDescription'));
    }
}
