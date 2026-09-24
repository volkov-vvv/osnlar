<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Agent\StoreRequest;
use App\Http\Requests\Admin\Agent\UpdateRequest;
use App\Models\Agent;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::all();
        return view('admin.agent.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agent.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Agent::firstOrCreate($data);

        return redirect()->route('admin.agent.index');
    }

    public function show(Agent $agent)
    {
        return view('admin.agent.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        return view('admin.agent.edit', compact('agent'));
    }

    public function update(UpdateRequest $request, Agent $agent)
    {
        $data = $request->validated();

        $agent->update($data);

        return redirect()->route('admin.agent.show', compact('agent'));
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agent.index');
    }
}
