<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Year\StoreRequest;
use App\Http\Requests\Admin\Year\UpdateRequest;
use App\Models\Year;

class YearController extends Controller
{
    public function index()
    {
        $years = Year::query()->ordered()->get();

        return view('admin.year.index', compact('years'));
    }

    public function create()
    {
        return view('admin.year.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['sort'] = $data['sort'] ?? 0;

        $year = Year::create($data);

        if (!empty($data['is_default'])) {
            $year->makeDefault();
        }

        return redirect()->route('admin.year.index');
    }

    public function show(Year $year)
    {
        return view('admin.year.show', compact('year'));
    }

    public function edit(Year $year)
    {
        return view('admin.year.edit', compact('year'));
    }

    public function update(UpdateRequest $request, Year $year)
    {
        $data = $request->validated();
        $data['sort'] = $data['sort'] ?? 0;
        $wasDefault = $year->is_default;

        $year->update($data);

        if (!empty($data['is_default'])) {
            $year->makeDefault();
        } elseif ($wasDefault) {
            $fallback = Year::query()
                ->active()
                ->where('id', '!=', $year->id)
                ->ordered()
                ->first();
            if ($fallback) {
                $fallback->makeDefault();
            } else {
                $year->makeDefault();
            }
        }

        return redirect()->route('admin.year.show', compact('year'));
    }

    public function destroy(Year $year)
    {
        $wasDefault = $year->is_default;
        $year->delete();

        if ($wasDefault) {
            $fallback = Year::query()->active()->ordered()->first();
            if ($fallback) {
                $fallback->makeDefault();
            }
        }

        return redirect()->route('admin.year.index');
    }
}
