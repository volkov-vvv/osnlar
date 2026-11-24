<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\UserDocument;

class BioLidStatsController extends Controller
{
    public function __invoke()
    {
        $bioLids = BioLid::query()->with(['documents', 'status'])->get();

        $docsStats = [
            'empty' => [
                'label' => 'Не отправлено',
                'count' => 0,
                'color' => '#6c757d',
            ],
            'awaiting_stage2' => [
                'label' => 'Ожидает этап 2',
                'count' => 0,
                'color' => '#17a2b8',
            ],
            UserDocument::REVIEW_PENDING => [
                'label' => UserDocument::REVIEW_STATUSES[UserDocument::REVIEW_PENDING],
                'count' => 0,
                'color' => '#ffc107',
            ],
            UserDocument::REVIEW_REJECTED => [
                'label' => UserDocument::REVIEW_STATUSES[UserDocument::REVIEW_REJECTED],
                'count' => 0,
                'color' => '#dc3545',
            ],
            UserDocument::REVIEW_APPROVED => [
                'label' => UserDocument::REVIEW_STATUSES[UserDocument::REVIEW_APPROVED],
                'count' => 0,
                'color' => '#28a745',
            ],
        ];

        $highParts = [
            UserDocument::REVIEW_APPROVED => [
                'label' => 'Проверено (без АРПМ «Замечания»)',
                'count' => 0,
            ],
            UserDocument::REVIEW_REJECTED => [
                'label' => 'Отклонено',
                'count' => 0,
            ],
            'awaiting_stage2' => [
                'label' => 'Ожидает этап 2',
                'count' => 0,
            ],
            UserDocument::REVIEW_PENDING => [
                'label' => 'На проверке',
                'count' => 0,
            ],
        ];
        $potential = 0;

        foreach ($bioLids as $bioLid) {
            $summaryStatus = $bioLid->documentsReviewSummary()['status'];
            if (! isset($docsStats[$summaryStatus])) {
                $summaryStatus = 'empty';
            }
            $docsStats[$summaryStatus]['count']++;

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
            'docsStats' => $docsStats,
            'funnel' => [
                'high' => $high,
                'highParts' => $highParts,
                'potential' => $potential,
            ],
        ];

        return view('admin.biolid.stats', compact('data'));
    }
}
