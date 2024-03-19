<?php

namespace App\Http\Controllers\CC\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\CC\Lid\StoreRequest;
use App\Models\Lid;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        Lid::firstOrCreate($data);

        return redirect()->route('cc.lid.index');

    }
}
