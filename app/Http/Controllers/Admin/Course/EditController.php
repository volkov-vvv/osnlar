<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSeries;
use Illuminate\Http\Request;

class EditController extends BaseController
{
    public function __invoke(Course $course)
    {

        $authors = Author::all();
        $series = CourseSeries::all();
        $years = [
            "1" => 2024,
            "2" => 2025,
        ];
        return view('admin.course.edit', compact('course', 'authors','series','years'));
    }
}
