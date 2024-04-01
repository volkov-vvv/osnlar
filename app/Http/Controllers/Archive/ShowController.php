<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Course;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Course $course)
    {
        $authors = Author::all();
        return view('archive.show',compact('course','authors'));
    }
}
