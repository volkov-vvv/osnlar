<?php

namespace App\Http\Controllers\Admin\Region;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Region;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Region $region)
    {
        $data = $request->validated();
        $region->update($data);

        return redirect()->route('admin.region.show', compact('region'));

    }
}
