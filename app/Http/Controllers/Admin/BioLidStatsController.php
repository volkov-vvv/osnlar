<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\StudyGroup;
use App\Models\UserDocument;
use Illuminate\Http\Request;

class BioLidStatsController extends Controller
{
    public function __invoke(Request $request)
    {
        $studyGroups = StudyGroup::query()->orderBy('title')->get();
        $studyGroupId = $request->integer('study_group_id') ?: null;
        if ($studyGroupId !== null && ! $studyGroups->contains('id', $studyGroupId)) {
            $studyGroupId = null;
        }

        $query = BioLid::query()->with(['documents', 'status']);
        if ($studyGroupId !== null) {
            $query->whereHas(
                'user.studyGroups',
                static fn ($q) => $q->where('study_groups.id', $studyGroupId)
            );
        }

        $bioLids = $query->get();

        $highParts = [
            UserDocument::REVIEW_APPROVED => [
                'label' => 'Проверено (без АРПМ «Замечания»)',
                'badge' => 'Проверено',
                'badgeClass' => 'badge badge-success',
                'count' => 0,
            ],
            UserDocument::REVIEW_REJECTED => [
                'label' => 'Отклонено',
                'badge' => 'Отклонено',
                'badgeClass' => 'badge badge-danger',
                'count' => 0,
            ],
            'awaiting_stage2' => [
                'label' => 'Ожидает этап 2',
                'badge' => 'Ожидает этап 2',
                'badgeClass' => 'badge badge-info',
                'count' => 0,
            ],
            UserDocument::REVIEW_PENDING => [
                'label' => 'На проверке',
                'badge' => 'На проверке',
                'badgeClass' => 'badge badge-warning',
                'count' => 0,
            ],
        ];
        $potential = 0;

        foreach ($bioLids as $bioLid) {
            $summaryStatus = $bioLid->documentsReviewSummary()['status'];
            if (! isset($highParts[$summaryStatus]) && $summaryStatus !== 'empty') {
                $summaryStatus = 'empty';
            }

            if (optional($bioLid->status)->title === 'Отказ') {
                continue;
            }

            if ($summaryStatus === 'empty') {
                $potential++;

                continue;
            }

            if ($summaryStatus === UserDocument::REVIEW_APPROVED
                && $bioLid->arpmStatusValue() === BioLid::ARPM_REMARKS
            ) {
                continue;
            }

            if (isset($highParts[$summaryStatus])) {
                $highParts[$summaryStatus]['count']++;
            }
        }

        $high = collect($highParts)->sum('count');

        $data = [
            'bioLidsCount' => $bioLids->count(),
            'funnel' => [
                'high' => $high,
                'highParts' => $highParts,
                'potential' => $potential,
            ],
        ];

        return view('admin.biolid.stats', compact('data', 'studyGroups', 'studyGroupId'));
    }
}
