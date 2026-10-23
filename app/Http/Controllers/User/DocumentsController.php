<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class DocumentsController extends Controller
{
    public function __invoke()
    {
        return view('user.documents');
    }
}
