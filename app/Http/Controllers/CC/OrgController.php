<?php

namespace App\Http\Controllers\CC;

use App\Http\Controllers\Controller;
use App\Http\Requests\CC\Org\UpdateRequest;
use App\Models\Course;
use App\Models\Org;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class OrgController extends Controller
{
    public function index()
    {
        $statuses = Status::all();
        $courses = Course::all();
        $orgs = Org::all();
        $users = User::where('role', 3)->get();
        return view('cc.org.index', compact('orgs','courses','statuses','users'));
    }

    public function show(Org $org)
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
            if(isset($activitiesStatuses['comment']))  $item->comment = $activitiesStatuses['comment']; else $item->comment = '';
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

    public function edit(Org $org)
    {
        $regions = Region::all();
        $statuses = Status::all();
        $users = User::where('role', 3)->get();
        return view('cc.org.edit', compact('org','statuses','users','regions'));
    }

    public function update(UpdateRequest $request, Org $org)
    {
        $data = $request->validated();
        $oldStatus = $org->status_id;
        $org->update($data);
        $actionDescription = 'Изменение информации';
        if ($oldStatus != $data['status_id']) $actionDescription = 'Изменение статуса';
        activity()->performedOn($org)->withProperties(['status_id_old' => $oldStatus, 'status_id' => $data['status_id'], 'comment' => $request->comment])->log($actionDescription);

        return redirect()->route('cc.org.show', compact('org'));
    }

    public function destroy(Org $org)
    {
        $org->delete();
        return redirect()->route('cc.org.index');
    }
}
