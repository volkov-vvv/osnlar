<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Models\Lid;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Lid $lid)
    {
        $statuses = Status::all();
        $users = User::where('role', 3)->get();
        return view('admin.lid.edit', compact('lid','statuses','users'));
    }
}
