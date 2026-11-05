<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Setting;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //Бесплатные курсы
    public function active()
    {
        $courses = Course::where('is_published', 1)->whereNull('price')->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->where(function ($query) {
            $query->where('code_future', '!=', 1)
                ->orWhereNull('code_future');
        })->where(function ($query) {
            $query->where('bioeconomy', '!=', 1)
                ->orWhereNull('bioeconomy');
        })->get()->sortBy('order');

        $filterHours = $courses
            ->pluck('study_hours')
            ->filter(fn ($hours) => $hours !== null && $hours !== '')
            ->map(fn ($hours) => (int) $hours)
            ->unique()
            ->sort()
            ->values();

        $filterForms = $courses
            ->pluck('study_form')
            ->filter(fn ($form) => is_string($form) && $form !== '' && isset(Course::STUDY_FORMS[$form]))
            ->unique()
            ->values()
            ->mapWithKeys(fn ($form) => [$form => Course::STUDY_FORMS[$form]]);

        $filterTypes = $courses
            ->pluck('type')
            ->filter(fn ($type) => is_string($type) && $type !== '' && isset(Course::TYPES[$type]))
            ->unique()
            ->values()
            ->mapWithKeys(fn ($type) => [$type => Course::TYPES[$type]]);

        $pageTitle = "Бесплатные курсы";
        $pageDescription = "Бесплатные курсы Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»";

        return view('course.index', compact(
            'courses',
            'pageTitle',
            'pageDescription',
            'filterHours',
            'filterForms',
            'filterTypes'
        ));
    }

    //Платные курсы
    public function commerce()
    {
        $courses = Course::where('is_published', 1)->whereNotNull('price')->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get()->sortBy('order');
        $pageTitle = "Платные курсы";
        $pageDescription = "Платные курсы Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»";
        return view('сommerce.index',compact('courses', 'pageTitle', 'pageDescription'));
    }

    //Код будущего
    public function future()
    {
        $courses = Course::where('is_published', 1)->where('code_future', 1)->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get()->sortBy('order');
        $pageTitle = "Код будущего";
        $pageDescription = "Курсы Учебного центра «Основание» по программе «Код будущег»";
        return view('future.index',compact('courses', 'pageTitle', 'pageDescription'));
    }

    //Биоэкономика
    public function bioeconomy()
    {
        $courses = Course::where('is_published', 1)->where('bioeconomy', 1)->where(function ($query) {
            $query->where('company_id', '=', 1)
                ->orWhereNull('company_id');
        })->get()->sortBy('order');
        $pageTitle = "Биоэкономика";
        $pageDescription = "Курсы Учебного центра «Основание» в рамках национального проекта «Технологическое обеспечение биоэкономики»";
        $documents = collect(Setting::bioeconomyDocuments())
            ->map(function (array $document) {
                $document['path'] = Setting::getValue($document['key']);

                return $document;
            })
            ->filter(fn (array $document) => filled($document['path']))
            ->values();

        return view('bioeconomy.index', compact('courses', 'pageTitle', 'pageDescription', 'documents'));
    }

}
