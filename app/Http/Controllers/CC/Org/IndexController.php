<?php

namespace App\Http\Controllers\CC\Org;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Org;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $statuses = Status::all();
        $courses = Course::all();
        $orgs = Org::all();
        $users = User::where('role', 3)->get();
        return view('cc.org.index', compact('orgs','courses','statuses','users'));
    }
}
