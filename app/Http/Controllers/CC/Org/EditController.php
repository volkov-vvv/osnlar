<?php

namespace App\Http\Controllers\CC\Org;

use App\Http\Controllers\Controller;

use App\Models\Org;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Org $org)
    {
        $regions = Region::all();
        $statuses = Status::all();
        $users = User::where('role', 3)->get();
        return view('cc.org.edit', compact('org','statuses','users','regions'));
    }
}
