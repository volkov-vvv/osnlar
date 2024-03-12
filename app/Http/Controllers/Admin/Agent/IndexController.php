<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $agents = Agent::all();
        return view('admin.agent.index', compact('agents'));
    }
}
