<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Lid\StoreRequest;
use App\Http\Requests\Admin\Lid\UpdateRequest;
use App\Mail\SendEmail;
use App\Models\Agent;
use App\Models\Category;
use App\Models\Leveledu;
use App\Models\Link;
use App\Models\Region;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Status;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use App\Exports\LidsExport;

class lidController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $courses = Course::all();
        $regions = Region::all();
        $statuses = Status::all();
        $users = User::where('role', 3)->get();

        return view('admin.lid.index', compact('courses','users','regions','statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        $regions = Region::all();
        $levelsedu = Leveledu::all();
        $categories = Category::all();
        $agents = Agent::all();
        $statuses = Status::all();
        $users = User::where('role', 3)->get();
        return view('admin.lid.create',  compact('levelsedu','regions','courses','categories','agents','statuses','users'));
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
        $lid = Lid::firstOrCreate($data);

        //Отправка письма
        if($request->send_mail){
            $links = Link::all()->where('region_id', $request->region_id)->where('course_id', $request->course_id)->last();
            if($links){
                $link = $links->link;
            }else{
                $link = '';
                $status = Status::all()->where('code', 'not-in-region')->first();
                $data['status_id'] =  $status->id;
            }
            $data['link'] = $link;
            $data['id'] = $lid->id;
            $mailData = collect($data);
            $mailData->subject = 'Ваша заявка на обучение принята';
            $mailData->template = 'mails.template';
            \Mail::to($data['email'])->send(new SendEmail($mailData));
        }


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

        return view('admin.lid.show', compact('lid','courses', 'activites','regions','categories','levels_edu'));
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

    /*
   AJAX request
   */
    public function getLids(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        if($columnName == 'course') $columnName = 'courses.title';
        if($columnName == 'region') $columnName = 'regions.title';
        if($columnName == 'responsible') $columnName = 'users.name';
        if($columnName == 'status') $columnName = 'statuses.title';
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $param['search'] = $searchValue;

        // Фильтры
        foreach ($columnName_arr as $key => $column) {
            if(!empty($column['search']['value'])){
                $param[$column['data']] = $column['search']['value'];
            }
        }

        $records = Lid::filter($param);

        $totalRecords = Lid::select('count(*) as allcount')->count();
        $totalRecordswithFilter = $records->count();

        $records = $records->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            if(isset($record->responsible)){
                $responsible = $record->responsible->name;
            }else{
                $responsible = '';
            }

            $agent = $record->agent->title;
            $course = $record->course->title;
            $region = $record->region->title;
            $lastname = $record->lastname;
            $firstname = $record->firstname;
            $email = $record->email;
            $created_at = $record->created_at;

            if($record->phone_prefix == '7'){
                $phone = '8' . $record->phone;
            }else{
                $phone = $record->phone_prefix . $record->phone;
            }

            $status = '<span class="badge rounded-pill" style="background-color: ' . $record->status->color . ' !important; color: ' . contrast_color($record->status->color) . '">'
                                                    . $record->status->title .
                                                '</span>';
            if($record->activity){
                $interval = dateDiff($record->activity->created_at, $record->created_at);
            }else{
                $interval = '---';
            }

            $actions = '<a href="' . route('admin.lid.show', $record->id) . '}">
                            <i class="far fa-eye"></i>
                        </a> &nbsp; &nbsp;
                        <a href="' . route('admin.lid.edit', $record->id) . '" class="text-success">
                            <i class="fas fa-pen"></i>
                        </a>';


            $data_arr[] = array(
                "id" => $id,
                "responsible" => $responsible,
                "agent" => $agent,
                "course" => $course,
                "region" => $region,
                "lastname" => $lastname,
                "firstname" => $firstname,
                "email" => $email,
                "phone" => $phone,
                "status" => $status,
                "interval" => $interval,
                "created_at" => $created_at,
                "actions" => $actions,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;

    }

    /*
     * Выгрузка в Excel
     */
    public function getLidsExcel(Request $request)
    {

        $columnSortName = $request->get('columnSortName');
        $columnSortOrder = $request->get('columnSortOrder');

        if($columnSortName == 'course') $columnSortName = 'courses.title';
        if($columnSortName == 'region') $columnSortName = 'regions.title';
        if($columnSortName == 'responsible') $columnSortName = 'users.name';
        if($columnSortName == 'status') $columnSortName = 'statuses.title';

        $param['search'] = $request->get('search');
        $param['responsible'] = $request->get('filterResponsible');
        $param['course'] = $request->get('filterCourse');
        $param['region'] = $request->get('filterRegion');
        $param['status'] = $request->get('filterStatus');

        return (new LidsExport)
            ->Params($param)
            ->Order($columnSortName, $columnSortOrder)
            ->download('lids.xlsx');
    }
}
