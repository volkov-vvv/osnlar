<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Status;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Carbon\Carbon;

class ShowController extends Controller
{
    public function __invoke(Lid $lid)
    {
        $statuses = Status::all();
        $courses = Course::all();
        $activites = Activity::all()->where('subject_id', '=', $lid['id'])->last();
        $properties = $activites->properties;

        $activitesStatus = Status::all()->where('id', '=', $properties['status_id'])->first();

        $act['user'] = User::findOrFail($activites->causer_id)->name;
        $act['action'] = 'изменение статуса';
        $act['new_val'] = $activitesStatus->title;
        $act['date'] =  Carbon::parse($activites->updated_at)->format('M d Y');

        return view('admin.lid.show', compact('lid','courses','statuses', 'act'));
    }
}
