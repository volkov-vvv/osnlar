<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboard(Request $request){
        $data_arr = array();
        $users = User::where('role', 3)->get();
        foreach($users as $user){
            $data_arr[] = array(
                'fio' => $user->name,
                'count' => $user->getActiveLids()['count'],
                'percent' => '<span class="badge bg-danger">' . $user->getActiveLids()['persent'] . '</span>',
                'average_time' => $user->averageTime(),
                'status_learning' => $user->getLids->where('status_id', 4)->count(),
                'status_pp' => $user->getLids->where('status_id', 6)->count(),
                'status_non_call' => $user->getLids->where('status_id', 2)->count(),
                'status_rejection' => $user->getLids->where('status_id', 9)->count(),
            );
        }

        $response = array(
            //"draw" => intval($draw),
            //"iTotalRecords" => $totalRecords,
            //"iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );


        return json_encode($response);
    }
}
