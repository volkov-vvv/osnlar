<?php

namespace App\Http\Controllers\CC\Status;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $statuses = Status::all();
        return view('cc.status.index', compact('statuses'));
    }
}
