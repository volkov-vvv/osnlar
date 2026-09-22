<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function __invoke()
    {
        return view('pages.video');
    }
}
