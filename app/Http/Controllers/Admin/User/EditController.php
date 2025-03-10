<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Company;
use App\Models\Lid;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(User $user)
    {

        $roles = $user->getRoles();
        $agents = Agent::all();
        $companies = Company::all();
        $utm_marks = Lid::all()->unique('utm_source')->values();
        $utm_sources = $utm_marks->pluck('utm_source');


        return view('admin.user.edit', compact('user','roles', 'agents', 'utm_marks', 'utm_sources', 'companies'));
    }
}
