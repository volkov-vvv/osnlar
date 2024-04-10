<?php

namespace App\Http\Controllers\Admin\About;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\About\StoreRequest;
use App\Models\About;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        About::firstOrCreate($data);

        return redirect()->route('admin.about.index');

    }
}
