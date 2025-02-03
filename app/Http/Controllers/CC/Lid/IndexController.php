<?php

namespace App\Http\Controllers\CC\Lid;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $companyId = 1;
        if(!empty($user->company_id)) $companyId = $user->company_id;
        $statuses = Status::all();
        $courses = Course::all();
        $lids = Lid::all();
        $users = User::where('role', 3)->get();
        return view('cc.lid.index', compact('lids','courses','statuses','users', 'companyId'));
    }
}
