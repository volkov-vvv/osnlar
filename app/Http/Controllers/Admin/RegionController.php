<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('admin.region.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.region.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Region::firstOrCreate($data);

        return redirect()->route('admin.region.index');
    }

    public function show(Region $region)
    {
        return view('admin.region.show', compact('region'));
    }

    public function edit(Region $region)
    {
        return view('admin.region.edit', compact('region'));
    }

    public function update(UpdateRequest $request, Region $region)
    {
        $data = $request->validated();
        $region->update($data);

        return redirect()->route('admin.region.show', compact('region'));
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.region.index');
    }
}
