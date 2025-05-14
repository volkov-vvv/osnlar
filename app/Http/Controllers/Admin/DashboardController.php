<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboard(Request $request){
        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $columnIndex_arr[0]['dir']; // asc or desc

        $param = array();
        foreach ($columnName_arr as $column) {
            if(!empty($column['search']['value'])){
                $param[$column['data']] = $column['search']['value'];
            }
        }

        if ( isset($param['year']) ) {
            $year = $param['year'];
        }else{
            $year = 2025;
        }


        $data_arr = array();
        $users = User::where('role', 3)->get();
        foreach($users as $user){
            $activeLids = $user->getActiveLids($year);
            $data_arr[] = array(
                'fio' => $user->name,
                'count' => $activeLids['count'],
                'percent' => $activeLids['persent'],
                'average_time' => $user->averageTime(),
                'status_learning' => $user->getLids->where('status_id', 4)->count(),
                'status_pp' => $user->getLids->where('status_id', 6)->count(),
                'status_non_call' => $user->getLids->where('status_id', 2)->count(),
                'status_rejection' => $user->getLids->where('status_id', 9)->count(),
                'year' => '2024'
            );
        }

        switch ($columnSortOrder) {
            case 'asc':
                $aaData = collect($data_arr)->sortBy($columnName)-> values()->toArray();
                break;
            case 'desc':
                $aaData = collect($data_arr)->sortByDesc($columnName)-> values()->toArray();
                break;
        }

        foreach($aaData as $key=>$item){
            $aaData[$key]['percent'] = '<span class="badge bg-danger">' . $item['percent'] . '</span>';
        }


        $response = array(
            //"draw" => intval($draw),
            "iTotalRecords" => count($aaData),
            "iTotalDisplayRecords" => count($users),
            "aaData" => $aaData
        );


        return json_encode($response);
    }
}
