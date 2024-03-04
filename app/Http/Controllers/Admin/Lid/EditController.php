<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Models\Lid;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Lid $lid)
    {
        return view('admin.lid.edit', compact('lid'));
    }
}
