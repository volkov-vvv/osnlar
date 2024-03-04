<?php

namespace App\Http\Controllers\Admin\Leveledu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Leveledu\StoreRequest;
use App\Models\Leveledu;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        Leveledu::firstOrCreate($data);

        return redirect()->route('admin.leveledu.index');

    }
}
