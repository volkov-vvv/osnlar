<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Leveledu\StoreRequest;
use App\Http\Requests\Admin\Leveledu\UpdateRequest;
use App\Models\Leveledu;

class LeveleduController extends Controller
{
    public function index()
    {
        $levelsedu = Leveledu::all();
        return view('admin.leveledu.index', compact('levelsedu'));
    }

    public function create()
    {
        return view('admin.leveledu.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Leveledu::firstOrCreate($data);

        return redirect()->route('admin.leveledu.index');
    }

    public function show(Leveledu $leveledu)
    {
        return view('admin.leveledu.show', compact('leveledu'));
    }

    public function edit(Leveledu $leveledu)
    {
        return view('admin.leveledu.edit', compact('leveledu'));
    }

    public function update(UpdateRequest $request, Leveledu $leveledu)
    {
        $data = $request->validated();
        $leveledu->update($data);

        return redirect()->route('admin.leveledu.show', compact('leveledu'));
    }

    public function destroy(Leveledu $leveledu)
    {
        $leveledu->delete();
        return redirect()->route('admin.leveledu.index');
    }
}
