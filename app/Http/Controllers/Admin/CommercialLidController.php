<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use App\Service\LidService;
use Illuminate\Http\Request;
use App\DTO\getLidsDTO;
use Spatie\DataTransferObject\DataTransferObject;

class CommercialLidController extends Controller
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

        $commerce = 1;


        return view('admin.commerciallid.index', compact('courses','users','regions','statuses', 'commerce'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
   AJAX request
   */
    public function getLids(Request $request, LidService $service)
    {
        $data = new getLidsDTO($request->all());


        $response = $service->getLids($data);

        echo $response;
        exit;
    }

}
