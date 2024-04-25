<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Lid\StoreRequest;
use App\Http\Requests\Admin\Lid\UpdateRequest;
use App\Models\Category;
use App\Models\Region;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Status;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;


class lidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::all();
        $courses = Course::all();
        $lids = Lid::all();
        $users = User::where('role', 3)->get();
        return view('admin.lid.index', compact('lids','courses','statuses','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.lid.create');
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

        return redirect()->route('admin.lid.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lid $lid)
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

        return view('admin.lid.show', compact('lid','courses','statuses', 'activites','regions','categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Lid $lid)
    {
        $statuses = Status::all();
        $regions = Region::all();
        $categories = Category::all();
        $users = User::where('role', 3)->get();
        return view('admin.lid.edit', compact('lid','statuses','users','regions','categories'));
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

        return redirect()->route('admin.lid.show', compact('lid'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lid $lid)
    {
        $lid->delete();
        return redirect()->route('admin.lid.index');
    }
}
