<?php

namespace App\Http\Controllers\CC\Status;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Status $status)
    {
        return view('cc.status.show', compact('status'));
    }
}
