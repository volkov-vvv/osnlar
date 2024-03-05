<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Course;
use App\Models\Leveledu;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke()
    {
        $categories = Category::all();
        $authors = Author::all();
        $levelsedu = Leveledu::all();
        $courses = Course::all();
        return view('lid.create', compact('categories', 'authors','levelsedu','courses'));
    }
}
