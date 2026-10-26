<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\UserDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DocumentsController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $bioLid = $this->resolveBioLid($user->id);
        $docs = [];

        if ($bioLid) {
            $docsCollection = UserDocument::query()
                ->where('user_id', $user->id)
                ->where('bio_lid_id', $bioLid->id)
                ->whereIn('type', array_keys(UserDocument::BIO_DOCS))
                ->latest('id')
                ->get();

            foreach ($docsCollection as $doc) {
                if (isset($docs[$doc->type])) {
                    continue;
                }

                $docs[$doc->type] = [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'file' => $doc->file,
                    'ext' => strtolower(pathinfo((string) $doc->file, PATHINFO_EXTENSION)),
                    'submitted_at' => $doc->submitted_at,
                    'review_status' => $doc->review_status,
                    'review_comment' => $doc->review_comment,
                    'reviewed_at' => $doc->reviewed_at,
                    'status_label' => $doc->reviewStatusLabel(),
                    'status_badge' => $doc->reviewStatusBadgeClass(),
                    'is_approved' => $doc->isApproved(),
                    'is_rejected' => $doc->isRejected(),
                    'is_pending' => $doc->isPendingReview(),
                ];
            }
        }

        return view('user.documents', [
            'bioLid' => $bioLid,
            'needDocs' => UserDocument::BIO_DOCS,
            'docs' => $docs,
        ]);
    }

    public function submitData(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $bioLid = $this->resolveBioLid($user->id);

        if (! $bioLid) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Заявка по БИО не найдена');
        }

        if ($bioLid->isDataApproved() || $bioLid->isDataPendingReview()) {
            return redirect()
                ->route('user.documents')
                ->with('success', 'Данные заявки уже отправлены на проверку');
        }

        $bioLid->forceFill([
            'data_submitted_at' => now(),
            'data_review_status' => UserDocument::REVIEW_PENDING,
            'data_review_comment' => null,
            'data_reviewed_at' => null,
            'data_reviewed_by' => null,
        ])->save();

        activity()
            ->causedBy($user)
            ->performedOn($bioLid)
            ->withProperties([
                'status_id_old' => $bioLid->status_id,
                'status_id' => $bioLid->status_id,
                'comment' => 'Пользователь отправил данные заявки на проверку',
            ])
            ->log('Отправка данных на проверку');

        return redirect()
            ->route('user.documents')
            ->with('success', 'Данные заявки отправлены на проверку');
    }

    public function submitDocument(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $bioLid = $this->resolveBioLid($user->id);

        if (! $bioLid) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Заявка по БИО не найдена');
        }

        $needDocs = UserDocument::BIO_DOCS;
        $type = (string) $request->input('type');
        $document = isset($needDocs[$type])
            ? $this->findDocument($user->id, $bioLid->id, $type)
            : null;

        if ($document && ($document->isApproved() || $document->isPendingReview())) {
            return redirect()
                ->route('user.documents')
                ->with('success', $needDocs[$type]['title'].' уже отправлен(а) на проверку');
        }

        $request->validate([
            'type' => ['required', Rule::in(array_keys($needDocs))],
            'doc' => [
                Rule::requiredIf(fn () => ! $document || $document->isRejected()),
                'nullable',
                'file',
                'mimes:'.($needDocs[$type]['mimes'] ?? 'pdf,jpg,jpeg,png'),
                'max:5120',
            ],
        ], [
            'doc.required' => 'Загрузите файл документа',
            'doc.mimes' => 'Допустимы файлы: PDF, JPG, PNG',
            'doc.max' => 'Размер файла не должен превышать 5 МБ',
        ]);

        $file = $request->file('doc');

        if ($file) {
            $path = $file->store('user_docs', 'public');

            if ($document) {
                if ($document->file && Storage::disk('public')->exists($document->file)) {
                    Storage::disk('public')->delete($document->file);
                }

                $document->update([
                    'title' => $file->getClientOriginalName(),
                    'file' => $path,
                    'submitted_at' => now(),
                    'review_status' => UserDocument::REVIEW_PENDING,
                    'review_comment' => null,
                    'reviewed_at' => null,
                    'reviewed_by' => null,
                ]);
            } else {
                $document = UserDocument::create([
                    'user_id' => $user->id,
                    'order_id' => null,
                    'bio_lid_id' => $bioLid->id,
                    'title' => $file->getClientOriginalName(),
                    'file' => $path,
                    'type' => $type,
                    'submitted_at' => now(),
                    'review_status' => UserDocument::REVIEW_PENDING,
                ]);
            }
        } elseif ($document) {
            $document->update([
                'submitted_at' => now(),
                'review_status' => UserDocument::REVIEW_PENDING,
                'review_comment' => null,
                'reviewed_at' => null,
                'reviewed_by' => null,
            ]);
        } else {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Загрузите файл документа');
        }

        activity()
            ->causedBy($user)
            ->performedOn($bioLid)
            ->withProperties([
                'status_id_old' => $bioLid->status_id,
                'status_id' => $bioLid->status_id,
                'comment' => 'Пользователь отправил на проверку: '.$needDocs[$type]['title'],
                'document_id' => $document->id,
                'document_type' => $type,
            ])
            ->log('Отправка документа на проверку');

        return redirect()
            ->route('user.documents')
            ->with('success', $needDocs[$type]['title'].' отправлен(а) на проверку');
    }

    protected function findDocument(int $userId, int $bioLidId, string $type): ?UserDocument
    {
        return UserDocument::query()
            ->where('user_id', $userId)
            ->where('bio_lid_id', $bioLidId)
            ->where('type', $type)
            ->latest('id')
            ->first();
    }

    protected function resolveBioLid(int $userId): ?BioLid
    {
        return BioLid::query()
            ->with('course')
            ->where('user_id', $userId)
            ->latest('id')
            ->first();
    }
}
