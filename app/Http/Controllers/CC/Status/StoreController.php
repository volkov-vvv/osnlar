<?php

namespace App\Http\Controllers\CC\Status;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Status\StoreRequest;
use App\Models\Status;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        Status::firstOrCreate($data);

        return redirect()->route('cc.status.index');

    }
}
