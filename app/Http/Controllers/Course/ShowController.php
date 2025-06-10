<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Course;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Course $course)
    {
        if($course->is_published != 0){
            $authors = Author::all();

            // SEO
            if(isset($course->seo_title)){
                $pageTitle = $course->seo_title;
            }else{
                $pageTitle = $course->title;
            }

            if(isset($course->seo_description)){
                $pageDescription = $course->seo_description;
            }else{
                $pageDescription = 'Бесплатное обучение по программе "' . $course->title . '"';
            }

            return view('course.show',compact('course','authors', 'pageTitle', 'pageDescription'));
        }else{
            return abort(404);
        }

    }
}
