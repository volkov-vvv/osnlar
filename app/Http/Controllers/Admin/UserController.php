<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\Agent;
use App\Models\Company;
use App\Models\Lid;
use App\Models\Org;
use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $user = new User();
        $roles = $user->getRoles();
        $users = User::with('studyGroups')->get();
        $studyGroups = StudyGroup::orderBy('title')->get();

        return view('admin.user.index', compact('users', 'roles', 'studyGroups'));
    }

    public function create()
    {
        $user = new User();
        $roles = $user->getRoles();
        $companies = Company::all();
        $studyGroups = StudyGroup::orderBy('title')->get();
        $organizations = Org::publishedBioeconomyOptions();

        return view('admin.user.create', compact('roles', 'companies', 'studyGroups', 'organizations'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $studyGroupIds = $data['study_group_ids'] ?? [];
        $orgIds = $data['org_ids'] ?? [];
        unset($data['study_group_ids'], $data['org_ids']);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->studyGroups()->sync($studyGroupIds);

        if ((int) $user->role === User::ROLE_AGENT) {
            $user->orgs()->sync($orgIds);
        }

        return redirect()->route('admin.user.index');
    }

    public function show(User $user)
    {
        $roles = $user->getRoles();
        $user->load(['studyGroups', 'orgs']);
        return view('admin.user.show', compact('user','roles'));
    }

    public function edit(User $user)
    {
        $roles = $user->getRoles();
        $agents = Agent::all();
        $companies = Company::all();
        $studyGroups = StudyGroup::orderBy('title')->get();
        $utm_marks = Lid::all()->unique('utm_source')->values();
        $utm_sources = $utm_marks->pluck('utm_source');
        $user->load(['studyGroups', 'orgs']);
        $organizations = Org::publishedBioeconomyOptions();
        $assignedTitles = $user->orgs
            ->map(static fn (Org $org) => mb_strtolower(trim((string) $org->organization_title)))
            ->filter()
            ->all();
        foreach ($user->orgs as $assignedOrg) {
            $titleKey = mb_strtolower(trim((string) $assignedOrg->organization_title));
            if ($titleKey === '') {
                continue;
            }
            $alreadyListed = $organizations->contains(
                static fn (Org $org) => mb_strtolower(trim((string) $org->organization_title)) === $titleKey
            );
            if (! $alreadyListed && in_array($titleKey, $assignedTitles, true)) {
                $organizations->push($assignedOrg);
            }
        }
        $organizations = $organizations
            ->sortBy(static fn (Org $org) => mb_strtolower(trim((string) $org->organization_title)), SORT_NATURAL)
            ->values();

        return view('admin.user.edit', compact(
            'user',
            'roles',
            'agents',
            'utm_marks',
            'utm_sources',
            'companies',
            'studyGroups',
            'organizations'
        ));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }else{
            unset($data['password']);
        }

        $studyGroupIds = $data['study_group_ids'] ?? [];
        $orgIds = $data['org_ids'] ?? [];
        unset($data['study_group_ids'], $data['org_ids'], $data['avatar']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        if(!isset($data['agent_ids'])){
            $data['agent_ids'] = array();
        }
        $user->agents()->sync($data['agent_ids']);
        $user->studyGroups()->sync($studyGroupIds);

        if ((int) $user->role === User::ROLE_AGENT) {
            $user->orgs()->sync($orgIds);
        } else {
            $user->orgs()->sync([]);
        }

        return redirect()->route('admin.user.show', compact('user'));
    }

    public function block(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            $message = 'Нельзя заблокировать собственный аккаунт.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()
                ->route('admin.user.index')
                ->with('error', $message);
        }

        $user->update([
            'is_blocked' => $request->boolean('is_blocked'),
        ]);

        $message = $user->is_blocked
            ? 'Пользователь заблокирован.'
            : 'Пользователь разблокирован.';

        if ($request->expectsJson()) {
            return response()->json([
                'is_blocked' => $user->is_blocked,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('admin.user.index')
            ->with('success', $message);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user.index');
    }
}
