<?php

namespace App\Http\Controllers\Admin\Main;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke()
    {
        $data = [];
        $data['usersCount'] = User::query()->count();
        $data['coursesCount'] = Course::query()->count();
        $data['lidsCount'] = Lid::query()->count();
        $data['usersKcCount'] = User::query()
            ->where('role', User::ROLE_CONTACT)
            ->where('is_blocked', false)
            ->count();

        return view('admin.main.index', compact('data'));
    }
}
