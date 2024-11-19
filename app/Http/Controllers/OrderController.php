<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Course $course)
    {
        $pageDescription = 'Заказ на обучение';
        return view('order.create', compact('pageDescription', 'course'));
    }

    public function store()
    {

    }
}
