<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $courses = Course::where('is_published', 0)->get()->reverse();
        $pageTitle = "Архив курсов";
        $pageDescription = "Архив курсов Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»";
        return view('archive.index',compact('courses', 'pageTitle', 'pageDescription'));
    }
}
