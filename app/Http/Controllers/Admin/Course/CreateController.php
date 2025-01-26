<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class CreateController extends BaseController
{
    public function __invoke()
    {

        $authors = Author::all();
        $companies = Company::all();
        return view('admin.course.create', compact('authors', 'companies'));
    }
}
