<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $homeUrl = $this->homeUrl();

        return view('user.profile', compact('user', 'homeUrl'));
    }

    public function update(UpdateRequest $request)
    {
        $user = auth()->user();
        $data = $request->safe()->only(['lastname', 'name']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Профиль сохранён');
    }

    private function homeUrl(): string
    {
        return match ((int) auth()->user()->role) {
            User::ROLE_ADMIN => route('admin.main.index'),
            User::ROLE_AGENT => route('agent.main.index'),
            User::ROLE_CONTACT => route('cc.main.index'),
            default => route('user.index'),
        };
    }
}
