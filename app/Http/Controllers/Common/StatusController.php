<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Concerns\ResolvesPanel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\Status\StoreRequest;
use App\Http\Requests\Common\Status\UpdateRequest;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StatusController extends Controller
{
    use ResolvesPanel;

    public function index(): View
    {
        return view($this->panelView('status.index'), [
            'statuses' => $this->statusesForIndex(),
        ]);
    }

    public function create(): View
    {
        return view($this->panelView('status.create'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Status::firstOrCreate($request->validated());

        return $this->panelRedirect('status.index');
    }

    public function show(Status $status): View
    {
        return view($this->panelView('status.show'), compact('status'));
    }

    public function edit(Status $status): View
    {
        return view($this->panelView('status.edit'), compact('status'));
    }

    public function update(UpdateRequest $request, Status $status): RedirectResponse
    {
        $status->update($request->validated());

        return $this->panelRedirect('status.show', compact('status'));
    }

    public function destroy(Status $status): RedirectResponse
    {
        $status->delete();

        return $this->panelRedirect('status.index');
    }

    protected function statusesForIndex(): Collection
    {
        return Status::query()
            ->when($this->panel() === 'admin', fn ($query) => $query->whereNull('type'))
            ->get();
    }
}
