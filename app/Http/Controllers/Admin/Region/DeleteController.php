<?php

namespace App\Http\Controllers\Admin\Region;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.region.index');

    }
}
