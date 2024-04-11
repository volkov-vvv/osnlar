<?php

namespace App\Http\Controllers\Admin\About;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(About $about)
    {
        return view('admin.about.edit', compact('about'));
    }
}
