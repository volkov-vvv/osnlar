<?php

namespace App\Http\Controllers\Org;

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
        $agents = Agent::all();
        $regions = Region::all();
        $courses = Course::where('is_published', 1)->get();
        return view('org.create', compact( 'courses','regions','agents'));
    }
}
