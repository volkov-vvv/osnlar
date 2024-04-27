<?php

namespace App\Http\Controllers\CC\Org;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Org;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Org $org)
    {
        $statuses = Status::all();
        $courses = Course::all();
        $regions = Region::all();

        // Формирование коллекции активности
        $activites = Activity::all()->where('subject_id', '=', $org['id']);
        $activites->each(function ($item, $key)  use ($statuses){
            $activitiesStatuses = $item->properties;
            $activitesStatusOld = $statuses->where('id',  $activitiesStatuses['status_id_old'])->first();
            $activitesStatusNew = $statuses->where('id',  $activitiesStatuses['status_id'])->first();
            $item->status_old = $activitesStatusOld->title;
            $item->status_new = $activitesStatusNew->title;
            $item->user = User::findOrFail($item->causer_id)->name;
        });

        // Определение времени первой реакции
        $firstActivity = Activity::all()->where('subject_id', '=', $org['id'])->where('description', '=', 'Изменение статуса')->first();
        if($firstActivity){
            $interval = date_diff($firstActivity->created_at, $org->created_at);
            $formatStr = '%hч %iмин';
            if ($interval->d > 0) $formatStr = '%dд ' . $formatStr;
            if ($interval->m > 0) $formatStr = '%mм ' . $formatStr;
            if ($interval->y > 0) $formatStr = '%yг ' . $formatStr;
            $activites->interval = $interval->format($formatStr);
        }else{
            $activites->interval = '---';
        }

        return view('cc.org.show', compact('org','courses','statuses','activites','regions'));
    }
}
