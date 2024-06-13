<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke()
    {
        $categories = Category::all();
        $agents = Agent::all();
        $regions = Region::all();
        $statuses = Status::all();
        $courses = Course::all();
        $users = User::where('role', 3)->get();
        $lids = Lid::all();
        $sources = $lids->unique('utm_source')->values()->all();
        dump('utm_source');
//      $lids = Lid::where('agent_id', Auth::user()->id)->get();
        return view('admin.report.index', compact('lids','courses','statuses','users','regions','agents','categories', 'sources'));
    }
}
