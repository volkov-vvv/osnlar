<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\Order;
use App\Models\UserDocument;

class IndexController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $userOrders = Order::query()
            ->with(['course', 'status'])
            ->where('customer_id', $user->id)
            ->get();

        $bioLid = BioLid::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();

        $rejectedDocuments = collect();

        if ($bioLid) {
            if ($bioLid->isDataRejected()) {
                $rejectedDocuments->push([
                    'title' => 'Данные заявки по БИО',
                    'comment' => $bioLid->data_review_comment,
                    'rejected_at' => $bioLid->data_reviewed_at,
                    'anchor' => 'docs-collapse-data',
                ]);
            }

            $docs = UserDocument::query()
                ->where('user_id', $user->id)
                ->where('bio_lid_id', $bioLid->id)
                ->whereIn('type', array_keys(UserDocument::BIO_DOCS))
                ->where('review_status', UserDocument::REVIEW_REJECTED)
                ->latest('id')
                ->get()
                ->unique('type');

            foreach ($docs as $doc) {
                $rejectedDocuments->push([
                    'title' => UserDocument::BIO_DOCS[$doc->type]['title'] ?? $doc->title,
                    'comment' => $doc->review_comment,
                    'rejected_at' => $doc->reviewed_at,
                    'anchor' => 'doc-'.$doc->type,
                ]);
            }
        }

        return view('user.index', compact('userOrders', 'rejectedDocuments'));
    }
}
