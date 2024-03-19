<?php

namespace App\Http\Controllers\CC\Status;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Status $status)
    {
        return view('cc.status.edit', compact('status'));
    }
}
