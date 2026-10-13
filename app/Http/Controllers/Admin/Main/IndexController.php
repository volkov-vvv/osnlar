<?php

namespace App\Http\Controllers\Admin\Main;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $data =[];
        $data['usersCount'] = User::all()->count();
        $data['coursesCount'] = Course::all()->count();
        $data['lidsCount'] = Lid::all()->count();
        $data['usersKcCount'] = User::where('role', 3)->count();

        $users = User::where('role', 3)->get();
        $years = Year::options();
        $defaultYear = Year::defaultValue();

        return view('admin.main.index',compact('data','users','years','defaultYear'));
    }
}
