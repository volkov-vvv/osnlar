<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Service\CourseService;

class BaseController extends Controller
{
    public $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;

    }


}
