<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Course;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $courses = Course::all();
        $companies = Company::all();
        return view('admin.course.index', compact('courses', 'companies'));
    }
}
