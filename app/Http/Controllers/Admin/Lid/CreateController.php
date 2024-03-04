<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('admin.lid.create');
    }
}
