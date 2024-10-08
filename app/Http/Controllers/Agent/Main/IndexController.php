<?php

namespace App\Http\Controllers\Agent\Main;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $data =[];
        $data['usersCount'] = User::all()->count();
        $data['coursesCount'] = Course::all()->count();
        $data['lidsCount'] = Lid::all()->count();

        return view('agent.main.index',compact('data'));
    }
}
