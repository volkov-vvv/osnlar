<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\About\StoreRequest;
use App\Http\Requests\Admin\About\UpdateRequest;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = About::all();
        return view('admin.about.index', compact('abouts'));
    }

    public function create()
    {
        return view('admin.about.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        About::firstOrCreate($data);

        return redirect()->route('admin.about.index');
    }

    public function show(About $about)
    {
        return view('admin.about.show', compact('about'));
    }

    public function edit(About $about)
    {
        return view('admin.about.edit', compact('about'));
    }

    public function update(UpdateRequest $request, About $about)
    {
        $data = $request->validated();
        $about->update($data);

        return redirect()->route('admin.about.show', compact('about'));
    }

    public function destroy(About $about)
    {
        $about->delete();
        return redirect()->route('admin.about.index');
    }
}
