<?php

namespace App\Http\Controllers\CC;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Status\StoreRequest;
use App\Http\Requests\Admin\Status\UpdateRequest;
use App\Models\Status;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::all();
        return view('cc.status.index', compact('statuses'));
    }

    public function create()
    {
        return view('cc.status.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Status::firstOrCreate($data);

        return redirect()->route('cc.status.index');
    }

    public function show(Status $status)
    {
        return view('cc.status.show', compact('status'));
    }

    public function edit(Status $status)
    {
        return view('cc.status.edit', compact('status'));
    }

    public function update(UpdateRequest $request, Status $status)
    {
        $data = $request->validated();
        $status->update($data);

        return redirect()->route('cc.status.show', compact('status'));
    }

    public function destroy(Status $status)
    {
        $status->delete();
        return redirect()->route('cc.status.index');
    }
}
