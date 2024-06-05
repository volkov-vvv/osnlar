<?php

namespace App\Http\Controllers\lid;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Author;
use App\Models\Category;
use App\Models\Course;
use App\Models\Leveledu;
use App\Models\Region;
use Illuminate\Http\Request;


class CreateNewController extends Controller
{
    public function __invoke($selectedCourse = null)
    {
        $categories = Category::whereNotIn('id',[4,5])->get()->sortBy('order');
        $categoriesMain = Category::find([4,5])->sortBy('order');
        $agents = Agent::all();
        $regions = Region::all();
        $authors = Author::all();
        $levelsedu = Leveledu::all();
        $courses = Course::where('is_published', 1)->get();
        return view('lid.create_new', compact('categories', 'authors','levelsedu','courses','regions','agents','categoriesMain', 'selectedCourse'));
    }

}
