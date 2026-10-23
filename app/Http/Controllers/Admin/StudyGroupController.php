<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudyGroup\StoreRequest;
use App\Http\Requests\Admin\StudyGroup\UpdateRequest;
use App\Models\Course;
use App\Models\StudyGroup;
use App\Models\User;

class StudyGroupController extends Controller
{
    public function index()
    {
        $studyGroups = StudyGroup::query()
            ->with('course')
            ->withCount('users')
            ->orderBy('title')
            ->get();

        $courses = Course::query()
            ->whereHas('studyGroups')
            ->orderBy('title')
            ->get();

        return view('admin.studygroup.index', compact('studyGroups', 'courses'));
    }

    public function create()
    {
        $courses = Course::query()->orderBy('title')->get();

        return view('admin.studygroup.create', compact('courses'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        StudyGroup::create($data);

        return redirect()->route('admin.studygroup.index');
    }

    public function show(StudyGroup $studygroup)
    {
        $studygroup->load(['users', 'course']);

        return view('admin.studygroup.show', compact('studygroup'));
    }

    public function edit(StudyGroup $studygroup)
    {
        $users = User::orderBy('lastname')->orderBy('name')->get();
        $courses = Course::query()->orderBy('title')->get();
        $studygroup->load(['users', 'course']);

        return view('admin.studygroup.edit', compact('studygroup', 'users', 'courses'));
    }

    public function update(UpdateRequest $request, StudyGroup $studygroup)
    {
        $data = $request->validated();
        $userIds = $data['user_ids'] ?? [];
        unset($data['user_ids']);

        $studygroup->update($data);
        $studygroup->users()->sync($userIds);

        return redirect()->route('admin.studygroup.show', $studygroup);
    }

    public function destroy(StudyGroup $studygroup)
    {
        $studygroup->users()->detach();
        $studygroup->delete();

        return redirect()->route('admin.studygroup.index');
    }
}
