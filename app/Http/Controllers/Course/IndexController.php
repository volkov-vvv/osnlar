<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //Бесплатные курсы
    public function active()
    {
        $courses = Course::where('is_published', 1)->whereNull('price')->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get()->sortDesc();

        $coursesS1 = Course::where('is_published', 1)->where('series', 1)->whereNull('price')->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get()->sortDesc();
        $coursesS2 = Course::where('is_published', 1)->where('series', 2)->whereNull('price')->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get()->sortDesc();
        $coursesS3 = Course::where('is_published', 1)->where('series', 3)->whereNull('price')->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get()->sortDesc();
        $pageTitle = "Курсы";
        $pageDescription = "Бесплатные курсы Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»";
        return view('course.index',compact('courses', 'pageTitle', 'pageDescription','coursesS1','coursesS2','coursesS3'));
    }

    //Платные курсы
    public function commerce()
    {
        $courses = Course::where('is_published', 1)->whereNotNull('price')->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get();
        $pageTitle = "Платные курсы";
        $pageDescription = "Платные курсы Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»";
        return view('сommerce.index',compact('courses', 'pageTitle', 'pageDescription'));
    }
}
