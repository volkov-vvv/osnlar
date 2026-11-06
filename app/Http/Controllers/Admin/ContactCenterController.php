<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Year;

class ContactCenterController extends Controller
{
    public function index()
    {
        $years = Year::options();
        $defaultYear = Year::defaultValue();

        return view('admin.contactcenter.index', compact('years', 'defaultYear'));
    }
}
