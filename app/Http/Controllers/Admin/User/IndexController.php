<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;


class IndexController extends Controller
{
    public function __invoke()
    {
        $user = new User();
        $roles = $user->getRoles();
        $users = User::all();
        return view('admin.user.index', compact('users','roles'));
    }
}
