<?php

namespace App\Http\Controllers\CC;

use App\Http\Controllers\Controller;
use App\Http\Requests\CC\Lid\StoreRequest;
use App\Http\Requests\CC\Lid\UpdateRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Leveledu;
use App\Models\Lid;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LidController extends Controller
{
    private function dateDiff($dateFirst, $dateSecond){
        $interval = date_diff($dateFirst, $dateSecond);
        $formatStr = '%hч %iмин';
        if ($interval->d > 0) $formatStr = '%dд ' . $formatStr;
        if ($interval->m > 0) $formatStr = '%mм ' . $formatStr;
        if ($interval->y > 0) $formatStr = '%yг ' . $formatStr;
        return $interval->format($formatStr);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
        $statuses = Status::all();
        $lids = Lid::all();
        $regions = Region::all();
        $users = User::where('role', 3)->get();

        $lids->each(function ($item, $key){
            if($item->activity){
                $item->interval = $this->dateDiff($item->activity->created_at, $item->created_at);
            }else{
                $item->interval = '---';
            }
        });

        return view('cc.lid.index', compact('lids','courses', 'users','regions', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cc.lid.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Lid::firstOrCreate($data);

        return redirect()->route('cc.lid.index');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Lid $lid)
    {
        $statuses = Status::all();
        $courses = Course::all();
        $regions = Region::all();
        $categories = Category::all();
        $levels_edu = Leveledu::all();

        // Формирование коллекции активности
        $activites = Activity::all()->where('subject_id', '=', $lid['id']);
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
        $firstActivity = Activity::all()->where('subject_id', '=', $lid['id'])->where('description', '=', 'Изменение статуса')->first();
        if($firstActivity){
            $interval = date_diff($firstActivity->created_at, $lid->created_at);
            $formatStr = '%hч %iмин';
            if ($interval->d > 0) $formatStr = '%dд ' . $formatStr;
            if ($interval->m > 0) $formatStr = '%mм ' . $formatStr;
            if ($interval->y > 0) $formatStr = '%yг ' . $formatStr;
            $activites->interval = $interval->format($formatStr);
        }else{
            $activites->interval = '---';
        }


        return view('cc.lid.show', compact('lid','courses','statuses','activites','regions','categories','levels_edu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Lid $lid)
    {
        $statuses = Status::all();
        $regions = Region::all();
        $categories = Category::all();
        $users = User::where('role', 3)->get();
        $courses = Course::all();
        return view('cc.lid.edit', compact('lid','statuses','users','categories','regions','courses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Lid $lid)
    {
        $data = $request->validated();
        $oldStatus = $lid->status_id;
        $lid->update($data);
        $actionDescription = 'Изменение информации';
        if ($oldStatus != $data['status_id']) $actionDescription = 'Изменение статуса';
        activity()->performedOn($lid)->withProperties(['status_id_old' => $oldStatus, 'status_id' => $data['status_id'], 'comment' => $request->comment])->log($actionDescription);

        return redirect()->route('cc.lid.show', compact('lid'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lid $lid)
    {
        $lid->delete();
        return redirect()->route('cc.lid.index');
    }
}
