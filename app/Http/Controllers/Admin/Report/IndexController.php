<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke()
    {
        $categories = Category::all();
        $agents = Agent::all();
        $regions = Region::all();
        $statuses = Status::all();
        $courses = Course::all();
        $users = User::where('role', 3)->get();
        $lids = Lid::all();
        $sources = $lids->unique('utm_source')->values()->all();
        $utmMedium = $lids->unique('utm_medium')->values()->all();

        return view('admin.report.index', compact('lids','courses','statuses','users','regions','agents','categories', 'sources', 'utmMedium'));
    }

    public function getReport(Request $request)
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
        if($columnName == 'agent') $columnName = 'agents.title';
        if($columnName == 'category') $columnName = 'categories.title';
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
            if($record->category){
                $category = $record->category->title;
            }else{
                $category = '';
            }

            if($record->agent){
                $agent = $record->agent->title;
            }else{
                $agent = '';
            }

            $course = $record->course->title;
            $region = $record->region->title;

            $lastname = $record->lastname;
            $firstname = $record->firstname;
            $email = $record->email;
            $created_at = $record->created_at->toDateTimeString();

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
            $utm_source = $record->utm_source;
            $utm_medium = $record->utm_medium;
            $utm_campaign = $record->utm_campaign;


            $data_arr[] = array(
                "id" => $id,
                "responsible" => $responsible,
                "agent" => $agent,
                "course" => $course,
                "region" => $region,
                "lastname" => $lastname,
                "firstname" => $firstname,
                "category" => $category,
                "email" => $email,
                "phone" => $phone,
                "status" => $status,
                "interval" => $interval,
                "created_at" => $created_at,
                "actions" => $actions,
                "utm_source" => $utm_source,
                "utm_medium" => $utm_medium,
                "utm_campaign" => $utm_campaign,
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
    public function getReportExcel(Request $request)
    {

        $columnSortName = $request->get('columnSortName');
        $columnSortOrder = $request->get('columnSortOrder');

        if($columnSortName == 'course') $columnSortName = 'courses.title';
        if($columnSortName == 'region') $columnSortName = 'regions.title';
        if($columnSortName == 'responsible') $columnSortName = 'users.name';
        if($columnSortName == 'status') $columnSortName = 'statuses.title';
        if($columnSortName == 'agent') $columnSortName = 'agents.title';
        if($columnSortName == 'category') $columnSortName = 'categories.title';


        $param['search'] = $request->get('search');
        $param['agent'] = $request->get('filterAgent');
        $param['status'] = $request->get('filterStatus');
        $param['utm_source'] = $request->get('filterUtm');


        return (new ReportExport)
            ->Params($param)
            ->Order($columnSortName, $columnSortOrder)
            ->download('report.xlsx');
    }
}
