<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agent.index');

    }
}
