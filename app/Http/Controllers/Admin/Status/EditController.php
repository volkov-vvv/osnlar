<?php

namespace App\Http\Controllers\Admin\Status;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Status $status)
    {
        return view('admin.status.edit', compact('status'));
    }
}
