<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Course;
use App\Models\Document;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()    {

        return view('services.index');
    }
}
