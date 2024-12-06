<?php


namespace App\Service;


use App\DTO\getLidsDTO;
use App\Models\Lid;
use Illuminate\Http\Request;

class LidService
{
    public function getLids(getLidsDTO $data)
    {

        $route_name = [
            0 => 'lid',
            1 => 'commerciallid'
        ];
        ## Read value
        $draw = $data->draw;

        $start = $data->start;
        $rowperpage = $data->length; // Rows display per page


        $columnIndex_arr = $data->order;
        $columnName_arr = $data->columns;
        $order_arr = $data->order;
        $search_arr = $data->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        if($columnName == 'course') $columnName = 'courses.title';
        if($columnName == 'region') $columnName = 'regions.title';
        if($columnName == 'responsible') $columnName = 'users.name';
        if($columnName == 'status') $columnName = 'statuses.title';
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $param['search'] = $searchValue;
        $param['commerce'] = $data->commerce;


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
            if(isset($record->responsible_name)){
                $responsible = $record->responsible_name;
            }else{
                $responsible = '';
            }
            if($record->agent_title){
                $agent = $record->agent_title;
            }else{
                $agent = '';
            }

            $course = $record->course_title;
            $region = $record->region_title;
            $lastname = $record->lastname;
            $firstname = $record->firstname;
            $email = $record->email;
            $created_at = $record->created_at->toDateTimeString();

            if($record->phone_prefix == '7'){
                $phone = '8' . $record->phone;
            }else{
                $phone = $record->phone_prefix . $record->phone;
            }

            $status = '<span class="badge rounded-pill" style="background-color: ' . $record->status_color . ' !important; color: ' . contrast_color($record->status_color) . '">'
                . $record->status_title .
                '</span>';
            if($record->activity){
                $interval = dateDiff($record->activity->created_at, $record->created_at);
            }else{
                $interval = '---';
            }



            $actions = '<a href="' . route('admin.' . $route_name[$data->commerce] . '.show', $record->id) . '}">
                            <i class="far fa-eye"></i>
                        </a> &nbsp; &nbsp;
                        <a href="' . route('admin.' . $route_name[$data->commerce] . '.edit', $record->id) . '" class="text-success">
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


        return json_encode($response);

    }

}
