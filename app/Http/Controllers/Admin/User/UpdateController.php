<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, User $user)
    {

        $data = $request->validated();
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }else{
            unset($data['password']);
        }
//        dd($data);
        $user->update($data);
        if(!isset($data['agent_ids'])){
            $data['agent_ids'] = array();
        }
        $user->agents()->sync($data['agent_ids']);
        return redirect()->route('admin.user.show', compact('user'));

    }
}
