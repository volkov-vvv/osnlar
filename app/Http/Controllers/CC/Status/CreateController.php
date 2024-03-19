<?php

namespace App\Http\Controllers\CC\Status;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('cc.status.create');
    }
}
