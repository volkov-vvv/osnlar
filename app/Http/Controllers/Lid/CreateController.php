<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Agent;
use App\Models\Category;
use App\Models\Course;
use App\Models\Leveledu;
use App\Models\Region;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke()
    {
        $categories = Category::all();
        $agents = Agent::all();
        $regions = Region::all();
        $authors = Author::all();
        $levelsedu = Leveledu::all();
        $courses = Course::all();
        return view('lid.create', compact('categories', 'authors','levelsedu','courses','regions','agents'));
    }
}
