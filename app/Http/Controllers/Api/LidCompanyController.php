<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LidRequest;
use App\Mail\SendEmail;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Region;
use Illuminate\Http\Request;

class LidCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LidRequest $request)
    {
        $data = $request->validated();

        $course = Course::query()->findOrFail($data['course_id']);
        if (!$course->default_status_id) {
            abort(422, 'У курса не задан статус по умолчанию');
        }
        $data['status_id'] = $course->default_status_id;

        $lid = Lid::firstOrCreate($data);

        $data['id'] = $lid->id;
        $mailData = collect($data);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = 'mails.template';
        \Mail::to($data['email'])->send(new SendEmail($mailData));

        return 'OK';
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
}
