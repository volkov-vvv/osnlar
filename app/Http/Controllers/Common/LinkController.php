<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Concerns\ResolvesPanel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\Link\StoreRequest;
use App\Http\Requests\Common\Link\UpdateRequest;
use App\Models\Course;
use App\Models\Link;
use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LinkController extends Controller
{
    use ResolvesPanel;

    public function index(): View
    {
        $links = Link::with(['region', 'course'])->get();

        return view($this->panelView('link.index'), compact('links'));
    }

    public function create(): View
    {
        $regions = Region::all();
        $courses = $this->coursesForCreate();

        return view($this->panelView('link.create'), compact('regions', 'courses'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Link::firstOrCreate($request->validated());

        return $this->panelRedirect('link.index');
    }

    public function show(Link $link): View
    {
        return view($this->panelView('link.show'), compact('link'));
    }

    public function edit(Link $link): View
    {
        $regions = Region::all();
        $courses = Course::all();

        return view($this->panelView('link.edit'), compact('link', 'regions', 'courses'));
    }

    public function update(UpdateRequest $request, Link $link): RedirectResponse
    {
        $link->update($request->validated());

        return $this->panelRedirect('link.show', compact('link'));
    }

    public function destroy(Link $link): RedirectResponse
    {
        $link->delete();

        return $this->panelRedirect('link.index');
    }

    protected function coursesForCreate(): Collection
    {
        return Course::query()
            ->when($this->panel() === 'admin', fn ($query) => $query->where('is_published', 1))
            ->get();
    }
}
