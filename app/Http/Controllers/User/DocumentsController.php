<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\UserDocument;
use App\Service\BioLidApplicationDocumentService;
use App\Service\BioLidConsentDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

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
                ->whereIn('type', array_keys(UserDocument::allBioDocs()))
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
            'applicationDocs' => UserDocument::BIO_APPLICATION_DOCS,
            'consentDocs' => UserDocument::BIO_CONSENT_DOCS,
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

        $data = $this->validateEditableBioData($request);

        $bioLid->forceFill([
            ...$data,
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

        $needDocs = UserDocument::allBioDocs();
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

    public function submitDocumentGroup(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $bioLid = $this->resolveBioLid($user->id);

        if (! $bioLid) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Заявка по БИО не найдена');
        }

        if (! $bioLid->areInitialDocumentStepsApproved()) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Шаги 6 и 7 откроются после проверки шагов 1–5');
        }

        $group = (string) $request->input('group');
        $groupDocs = match ($group) {
            'application' => UserDocument::BIO_APPLICATION_DOCS,
            'consents' => UserDocument::BIO_CONSENT_DOCS,
            default => null,
        };

        if ($groupDocs === null) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Неизвестная группа документов');
        }

        $openSection = $group === 'consents' ? 'consents' : 'application';
        $submittable = [];

        foreach ($groupDocs as $type => $needDoc) {
            $document = $this->findDocument($user->id, $bioLid->id, $type);
            $canSubmit = ! $document
                || $document->isRejected()
                || (! $document->isPendingReview() && ! $document->isApproved());

            if ($canSubmit) {
                $submittable[$type] = [
                    'need' => $needDoc,
                    'document' => $document,
                ];
            }
        }

        if ($submittable === []) {
            return redirect()
                ->route('user.documents')
                ->with('success', 'Документы этой группы уже отправлены на проверку')
                ->with('open_section', $openSection);
        }

        $rules = [
            'group' => ['required', Rule::in(['application', 'consents'])],
        ];
        $messages = [
            'docs.*.required' => 'Загрузите файл документа',
            'docs.*.mimes' => 'Допустимы файлы: PDF, JPG, PNG',
            'docs.*.max' => 'Размер файла не должен превышать 5 МБ',
        ];

        foreach ($submittable as $type => $item) {
            $optional = ! empty($item['need']['optional']);
            $rules['docs.'.$type] = [
                $optional ? 'nullable' : 'required',
                'file',
                'mimes:'.($item['need']['mimes'] ?? 'pdf,jpg,jpeg,png'),
                'max:5120',
            ];
        }

        $request->validate($rules, $messages);

        $uploadedTitles = [];

        foreach ($submittable as $type => $item) {
            $file = $request->file('docs.'.$type);
            if (! $file) {
                continue;
            }

            $path = $file->store('user_docs', 'public');
            $document = $item['document'];

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

            $uploadedTitles[] = $item['need']['title'];

            activity()
                ->causedBy($user)
                ->performedOn($bioLid)
                ->withProperties([
                    'status_id_old' => $bioLid->status_id,
                    'status_id' => $bioLid->status_id,
                    'comment' => 'Пользователь отправил на проверку: '.$item['need']['title'],
                    'document_id' => $document->id,
                    'document_type' => $type,
                ])
                ->log('Отправка документа на проверку');
        }

        if ($uploadedTitles === []) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Загрузите хотя бы один файл')
                ->with('open_section', $openSection);
        }

        $success = count($uploadedTitles) === 1
            ? $uploadedTitles[0].' отправлен(а) на проверку'
            : 'Отправлены на проверку: '.implode(', ', $uploadedTitles);

        return redirect()
            ->route('user.documents')
            ->with('success', $success)
            ->with('open_section', $openSection);
    }

    public function downloadApplication(BioLidApplicationDocumentService $applicationDocumentService): Response|RedirectResponse
    {
        $user = auth()->user();
        $bioLid = $this->resolveBioLid($user->id);

        if (! $bioLid) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Заявка по БИО не найдена');
        }

        if (! $bioLid->areInitialDocumentStepsApproved()) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Шаги 6 и 7 откроются после проверки шагов 1–5');
        }

        try {
            $document = $applicationDocumentService->generate($bioLid);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('user.documents')
                ->with('error', $exception->getMessage());
        }

        return response($document['binary'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$document['filename'].'"',
        ]);
    }

    public function downloadConsent(
        string $kind,
        BioLidConsentDocumentService $consentDocumentService
    ): Response|RedirectResponse {
        $user = auth()->user();
        $bioLid = $this->resolveBioLid($user->id);

        if (! $bioLid) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Заявка по БИО не найдена');
        }

        if (! $bioLid->areInitialDocumentStepsApproved()) {
            return redirect()
                ->route('user.documents')
                ->with('error', 'Шаги 6 и 7 откроются после проверки шагов 1–5');
        }

        $allowedKinds = array_values(array_map(
            static fn (array $doc) => $doc['kind'],
            UserDocument::BIO_CONSENT_DOCS
        ));

        if (! in_array($kind, $allowedKinds, true)) {
            abort(404);
        }

        try {
            $document = $consentDocumentService->generate($bioLid, $kind);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('user.documents')
                ->with('error', $exception->getMessage());
        }

        return response($document['binary'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$document['filename'].'"',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateEditableBioData(Request $request): array
    {
        $merged = [
            'residence_address' => trim((string) $request->input('residence_address')),
            'education' => trim((string) $request->input('education')),
            'education_organization' => trim((string) $request->input('education_organization')),
            'education_qualification' => trim((string) $request->input('education_qualification')),
            'diploma_series_number' => trim((string) $request->input('diploma_series_number')),
        ];

        $seriesDigits = preg_replace('/\D+/', '', (string) $request->input('passport_series')) ?? '';
        if (strlen($seriesDigits) === 4) {
            $merged['passport_series'] = substr($seriesDigits, 0, 2).' '.substr($seriesDigits, 2, 2);
        } else {
            $merged['passport_series'] = $seriesDigits;
        }

        $numberDigits = preg_replace('/\D+/', '', (string) $request->input('passport_number')) ?? '';
        $merged['passport_number'] = $numberDigits;

        $merged['passport_issued_by'] = trim((string) $request->input('passport_issued_by'));

        $departmentDigits = preg_replace('/\D+/', '', (string) $request->input('passport_department_code')) ?? '';
        if (strlen($departmentDigits) === 6) {
            $merged['passport_department_code'] = substr($departmentDigits, 0, 3).'–'.substr($departmentDigits, 3, 3);
        } else {
            $merged['passport_department_code'] = $departmentDigits;
        }

        $merged['passport_issued_at'] = $request->input('passport_issued_at');
        $merged['diploma_issued_at'] = $request->input('diploma_issued_at');

        $request->merge($merged);

        return $request->validate([
            'residence_address' => ['required', 'string', 'max:2000'],
            'education' => ['required', 'string', 'max:5000'],
            'education_organization' => ['required', 'string', 'max:255'],
            'education_qualification' => ['required', 'string', 'max:255'],
            'diploma_series_number' => ['required', 'string', 'max:100'],
            'diploma_issued_at' => ['required', 'date', 'before_or_equal:today'],
            'passport_series' => ['required', 'regex:/^\d{2} \d{2}$/'],
            'passport_number' => ['required', 'regex:/^\d{6}$/'],
            'passport_issued_by' => ['required', 'string', 'max:255'],
            'passport_issued_at' => ['required', 'date', 'before_or_equal:today'],
            'passport_department_code' => ['required', 'regex:/^\d{3}–\d{3}$/u'],
        ], [
            'residence_address.required' => 'Укажите адрес места жительства',
            'education.required' => 'Укажите сведения об образовании',
            'education_organization.required' => 'Укажите образовательную организацию',
            'education_qualification.required' => 'Укажите полученную квалификацию',
            'diploma_series_number.required' => 'Укажите серию и номер диплома',
            'diploma_issued_at.required' => 'Укажите дату выдачи диплома',
            'diploma_issued_at.before_or_equal' => 'Дата выдачи диплома не может быть в будущем',
            'passport_series.required' => 'Укажите серию паспорта',
            'passport_series.regex' => 'Серия паспорта должна быть в формате 00 00',
            'passport_number.required' => 'Укажите номер паспорта',
            'passport_number.regex' => 'Номер паспорта должен содержать 6 цифр',
            'passport_issued_by.required' => 'Укажите, кем выдан паспорт',
            'passport_issued_at.required' => 'Укажите дату выдачи паспорта',
            'passport_issued_at.before_or_equal' => 'Дата выдачи паспорта не может быть в будущем',
            'passport_department_code.required' => 'Укажите код подразделения',
            'passport_department_code.regex' => 'Код подразделения должен быть в формате 000–000',
        ]);
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
            ->with(['course.biolidStudyGroup'])
            ->where('user_id', $userId)
            ->latest('id')
            ->first();
    }
}
