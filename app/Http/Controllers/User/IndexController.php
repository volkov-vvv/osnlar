<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class IndexController extends Controller
{
    public function __invoke()    {

        dd('user account');
        return('Ok');
    }
}
