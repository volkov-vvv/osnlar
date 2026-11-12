<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\StoreRequest;
use App\Http\Requests\Admin\Project\UpdateRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('admin.project.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.project.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Project::firstOrCreate($data);

        return redirect()->route('admin.project.index');
    }

    public function show(Project $project)
    {
        return view('admin.project.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('admin.project.edit', compact('project'));
    }

    public function update(UpdateRequest $request, Project $project)
    {
        $data = $request->validated();
        $project->update($data);

        return redirect()->route('admin.project.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.project.index');
    }
}
