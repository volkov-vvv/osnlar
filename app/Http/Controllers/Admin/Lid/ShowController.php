<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Region;
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
        $regions = Region::all();
        $categories = Category::all();

        // Формирование коллекции активности
        $activites = Activity::all()->where('subject_id', '=', $lid['id']);
        $activites->each(function ($item, $key)  use ($statuses){
            $activitiesStatuses = $item->properties;
            $activitesStatusOld = $statuses->where('id',  $activitiesStatuses['status_id_old'])->first();
            $activitesStatusNew = $statuses->where('id',  $activitiesStatuses['status_id'])->first();
            $item->status_old = $activitesStatusOld->title;
            $item->status_new = $activitesStatusNew->title;
            $item->user = User::findOrFail($item->causer_id)->name;
        });

        return view('admin.lid.show', compact('lid','courses','statuses', 'activites','regions','categories'));
    }
}
