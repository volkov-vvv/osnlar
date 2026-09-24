<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Status\StoreRequest;
use App\Http\Requests\Admin\Status\UpdateRequest;
use App\Models\Status;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::whereNull('type')->get();
        return view('admin.status.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.status.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Status::firstOrCreate($data);

        return redirect()->route('admin.status.index');
    }

    public function show(Status $status)
    {
        return view('admin.status.show', compact('status'));
    }

    public function edit(Status $status)
    {
        return view('admin.status.edit', compact('status'));
    }

    public function update(UpdateRequest $request, Status $status)
    {
        $data = $request->validated();
        $status->update($data);

        return redirect()->route('admin.status.show', compact('status'));
    }

    public function destroy(Status $status)
    {
        $status->delete();
        return redirect()->route('admin.status.index');
    }
}
