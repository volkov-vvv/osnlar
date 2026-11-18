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
    public const COURSE_RANKING_DEFAULT_YEAR = 2027;

    public function __invoke(Request $request)
    {
        $data = [];
        $data['usersCount'] = User::query()->count();
        $data['coursesCount'] = Course::query()->count();
        $data['lidsCount'] = Lid::query()->count();
        $data['usersKcCount'] = User::query()
            ->where('role', User::ROLE_CONTACT)
            ->where('is_blocked', false)
            ->count();

        $years = Year::options();
        $selectedYear = (int) $request->get('year', self::COURSE_RANKING_DEFAULT_YEAR);
        if (!$years->contains($selectedYear)) {
            $selectedYear = self::COURSE_RANKING_DEFAULT_YEAR;
        }

        $courseRanking = Course::rankingByLidsForYear($selectedYear);

        return view('admin.main.index', compact('data', 'years', 'selectedYear', 'courseRanking'));
    }
}
