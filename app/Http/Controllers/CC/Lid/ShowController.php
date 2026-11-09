<?php

namespace App\Http\Controllers\CC\Lid;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lid;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Lid $lid)
    {
        $statuses = Status::all();
        $courses = Course::all();
        $regions = Region::all();
        $categories = Category::all();

        // Формирование коллекции активности
        $activites = Activity::query()
            ->where('subject_type', Lid::class)
            ->where('subject_id', $lid->id)
            ->orderBy('created_at')
            ->get();

        $userNames = User::query()
            ->whereIn('id', $activites->pluck('causer_id')->filter()->unique())
            ->pluck('name', 'id');

        $activites->each(function ($item) use ($statuses, $userNames) {
            $activitiesStatuses = $item->properties;
            $activitesStatusOld = $statuses->where('id', $activitiesStatuses['status_id_old'] ?? null)->first();
            $activitesStatusNew = $statuses->where('id', $activitiesStatuses['status_id'] ?? null)->first();
            $item->status_old = $activitesStatusOld->title ?? '—';
            $item->status_new = $activitesStatusNew->title ?? '—';
            $item->user = $userNames[$item->causer_id] ?? '—';
        });

        return view('cc.lid.show', compact('lid','courses','statuses','activites','regions','categories'));
    }
}
