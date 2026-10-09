<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Concerns\ResolvesPanel;
use App\Http\Controllers\Controller;
use App\Http\Requests\CC\Org\UpdateRequest;
use App\Models\Course;
use App\Models\Org;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class OrgController extends Controller
{
    use ResolvesPanel;

    public function index(): View
    {
        $statuses = Status::all();
        $courses = Course::all();
        $orgs = Org::with(['courses', 'responsible'])->latest()->get();
        $users = User::where('role', User::ROLE_CONTACT)->get();

        $panel = $this->panel();

        return view($this->panelView('org.index'), compact('orgs', 'courses', 'statuses', 'users', 'panel'));
    }

    public function show(Org $org): View
    {
        $org->load('courses');
        $statuses = Status::all();
        $courses = Course::all();
        $regions = Region::all();
        $activites = $this->orgActivities($org, $statuses);
        $panel = $this->panel();

        return view($this->panelView('org.show'), compact('org', 'courses', 'statuses', 'activites', 'regions', 'panel'));
    }

    public function edit(Org $org): View
    {
        $org->load('courses');
        $regions = Region::all();
        $statuses = Status::all();
        $users = User::where('role', User::ROLE_CONTACT)->get();
        $panel = $this->panel();

        return view($this->panelView('org.edit'), compact('org', 'statuses', 'users', 'regions', 'panel'));
    }

    public function update(UpdateRequest $request, Org $org): RedirectResponse
    {
        $data = $request->validated();
        $oldStatus = $org->status_id;
        $org->update($data);

        $actionDescription = $oldStatus != ($data['status_id'] ?? $oldStatus)
            ? 'Изменение статуса'
            : 'Изменение информации';

        activity()
            ->performedOn($org)
            ->withProperties([
                'status_id_old' => $oldStatus,
                'status_id' => $data['status_id'] ?? $oldStatus,
                'comment' => $request->comment,
            ])
            ->log($actionDescription);

        return $this->panelRedirect('org.show', compact('org'));
    }

    public function destroy(Org $org): RedirectResponse
    {
        $org->delete();

        return $this->panelRedirect('org.index');
    }

    protected function orgActivities(Org $org, Collection $statuses): Collection
    {
        $activites = Activity::all()->where('subject_id', '=', $org['id']);

        $activites->each(function ($item) use ($statuses) {
            $properties = $item->properties;
            $oldStatus = $statuses->where('id', $properties['status_id_old'] ?? null)->first();
            $newStatus = $statuses->where('id', $properties['status_id'] ?? null)->first();
            $item->status_old = $oldStatus->title ?? '—';
            $item->status_new = $newStatus->title ?? '—';
            $item->comment = $properties['comment'] ?? '';
            $item->user = User::find($item->causer_id)?->name ?? '—';
        });

        $firstActivity = Activity::all()
            ->where('subject_id', '=', $org['id'])
            ->where('description', '=', 'Изменение статуса')
            ->first();

        if ($firstActivity) {
            $interval = date_diff($firstActivity->created_at, $org->created_at);
            $formatStr = '%hч %iмин';
            if ($interval->d > 0) {
                $formatStr = '%dд ' . $formatStr;
            }
            if ($interval->m > 0) {
                $formatStr = '%mм ' . $formatStr;
            }
            if ($interval->y > 0) {
                $formatStr = '%yг ' . $formatStr;
            }
            $activites->interval = $interval->format($formatStr);
        } else {
            $activites->interval = '---';
        }

        return $activites;
    }
}
