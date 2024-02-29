<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class EditController extends BaseController
{
    public function __invoke(Course $course)
    {
        $categories = Category::all();
        $authors = Author::all();
        return view('admin.course.edit', compact('course', 'categories','authors'));
    }
}
