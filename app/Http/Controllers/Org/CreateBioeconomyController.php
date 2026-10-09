<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CreateBioeconomyController extends Controller
{
    public function __invoke()
    {
        $courses = Course::where('is_published', 1)
            ->where('bioeconomy', 1)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $pageTitle = 'Заявка от организации';
        $pageDescription = 'Заявка организации на обучение по программам Биоэкономики';

        return view('org.create_bioeconomy', compact('courses', 'pageTitle', 'pageDescription'));
    }
}
