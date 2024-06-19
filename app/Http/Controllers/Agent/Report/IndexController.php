<?php

namespace App\Http\Controllers\Agent\Report;

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
        $agents = auth()->user()->agents;
        $sources = auth()->user()->utm;
        $regions = Region::all();
        $statuses = Status::all();
        $courses = Course::all();
        $users = User::where('role', 3)->get();
        $lids = Lid::whereIn('agent_id', $agents->pluck('id')->toArray())->orwhereIn('utm_source', $sources)->get();
        $agentsForFilter = $lids->unique('agent_id')->values();
        $sourcesForFilter = $lids->unique('utm_source')->values();

        return view('agent.report.index', compact('lids','courses','statuses','users','regions','agentsForFilter','categories', 'sourcesForFilter'));
    }
}
