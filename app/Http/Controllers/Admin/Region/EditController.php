<?php

namespace App\Http\Controllers\Admin\Region;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Region $region)
    {
        return view('admin.region.edit', compact('region'));
    }
}
