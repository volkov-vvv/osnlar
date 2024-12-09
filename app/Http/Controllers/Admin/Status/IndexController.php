<?php

namespace App\Http\Controllers\Admin\Status;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $statuses = Status::whereNull('type')->get();
        return view('admin.status.index', compact('statuses'));
    }
}
