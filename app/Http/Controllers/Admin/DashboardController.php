<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lid;
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
            $year = 2026;
        }


        $data_arr = array();
        $users = User::where('role', 3)->get();

        $lids = new Lid();
        $lidsCount = $lids->getLidsCount($year);
        foreach($users as $user){
            $userLids = $user->getLids2($year);
            $activeLids = $user->getActiveLids($year);
            if($lidsCount !=0){
                $percent = round($activeLids / $lidsCount * 100, 2);
            }else{
                $percent = 0;
            }
            $data_arr[] = array(
                'fio' => $user->name,
                'count' => $activeLids,
                'percent' => $percent,
                'average_time' => $user->averageTime($year),
                'status_learning' => $userLids->where('status_id', 4)->count(),
                'status_end_learning' => $userLids->where('status_id', 23)->count(),
                'status_pp' => $userLids->where('status_id', 6)->count(),
                'status_non_call' => $userLids->where('status_id', 2)->count(),
                'status_rejection' => $userLids->where('status_id', 9)->count(),
                'year' => $year
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
