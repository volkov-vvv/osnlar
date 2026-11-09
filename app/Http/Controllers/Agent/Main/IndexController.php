<?php

namespace App\Http\Controllers\Agent\Main;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\UserDocument;

class IndexController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $titles = $user->bioOrganizationTitles();

        $bioLids = $titles === []
            ? collect()
            : BioLid::query()
                ->with('documents')
                ->whereIn('organization_title', $titles)
                ->get();

        $docsStats = [
            'empty' => [
                'label' => 'Не отправлено',
                'count' => 0,
                'color' => '#6c757d',
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

        foreach ($bioLids as $bioLid) {
            $status = $bioLid->documentsReviewSummary()['status'];
            if (! isset($docsStats[$status])) {
                $status = 'empty';
            }
            $docsStats[$status]['count']++;
        }

        $data = [
            'bioLidsCount' => $bioLids->count(),
            'docsStats' => $docsStats,
        ];

        return view('agent.main.index', compact('data'));
    }
}
