<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\Course;

class IndexController extends Controller
{
    public function __invoke()
    {
        $currentYear = (int) date('Y');

        $courses = Course::where('is_published', 0)
            ->orderByDesc('id')
            ->get();

        $years = $courses
            ->pluck('years')
            ->filter()
            ->map(fn ($year) => (int) $year)
            ->unique();

        if (!$years->contains($currentYear)) {
            $years->push($currentYear);
        }

        $years = $years->sortDesc()->values();

        $pageTitle = 'Архив курсов';
        $pageDescription = 'Архив курсов Учебного центра «Основание» по программам дополнительного профессионального образования в рамках реализации федерального проекта «Содействие занятости»';

        return view('archive.index', compact(
            'courses',
            'years',
            'currentYear',
            'pageTitle',
            'pageDescription'
        ));
    }
}
