<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\Agent;
use App\Models\Company;
use App\Models\Lid;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = new User();
        $roles = $user->getRoles();
        $users = User::all();
        return view('admin.user.index', compact('users','roles'));
    }

    public function create()
    {
        $user = new User();
        $roles = $user->getRoles();
        $companies = Company::all();
        return view('admin.user.create', compact('roles', 'companies'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::firstOrCreate($data);

        return redirect()->route('admin.user.index');
    }

    public function show(User $user)
    {
        $roles = $user->getRoles();
        return view('admin.user.show', compact('user','roles'));
    }

    public function edit(User $user)
    {
        $roles = $user->getRoles();
        $agents = Agent::all();
        $companies = Company::all();
        $utm_marks = Lid::all()->unique('utm_source')->values();
        $utm_sources = $utm_marks->pluck('utm_source');

        return view('admin.user.edit', compact('user','roles', 'agents', 'utm_marks', 'utm_sources', 'companies'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }else{
            unset($data['password']);
        }

        $user->update($data);
        if(!isset($data['agent_ids'])){
            $data['agent_ids'] = array();
        }
        $user->agents()->sync($data['agent_ids']);
        return redirect()->route('admin.user.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user.index');
    }
}
