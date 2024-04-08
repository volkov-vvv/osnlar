<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lid\StoreRequest;
use App\Models\Lid;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        Lid::firstOrCreate($data);
//        dd($data['email']);
        \Mail::to($data['email'])->send(new \App\Mail\SendEmail($data));

        return redirect()->route('lid.index');

    }
}
