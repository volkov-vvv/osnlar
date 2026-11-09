<?php

namespace App\Http\Controllers\Common;

use App\Exports\BioLidsQuestionnaireExport;
use App\Http\Controllers\Concerns\ResolvesPanel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BioLid\StoreRequest;
use App\Http\Requests\Admin\BioLid\UpdateRequest;
use App\Models\BioLid;
use App\Models\Course;
use App\Models\Org;
use App\Models\Status;
use App\Models\StudyGroup;
use App\Models\User;
use App\Models\UserDocument;
use App\Service\BioLidAutomationService;
use App\Service\BioLidDocumentsArchiveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BioLidController extends Controller
{
    use ResolvesPanel;

    public function __construct(private BioLidAutomationService $automation)
    {
    }

    public function index()
    {
        $bioLidsQuery = BioLid::query()
            ->with(['course', 'status', 'responsible', 'user.studyGroups', 'documents'])
            ->latest();
        $this->applyAgentOrganizationScope($bioLidsQuery);
        $bioLids = $bioLidsQuery->get();

        $courses = Course::query()
            ->where('bioeconomy', 1)
            ->orderBy('title')
            ->get();

        $organizationsQuery = BioLid::query()
            ->whereNotNull('organization_title')
            ->where('organization_title', '!=', '');
        $this->applyAgentOrganizationScope($organizationsQuery);
        $organizations = $organizationsQuery
            ->distinct()
            ->orderBy('organization_title')
            ->pluck('organization_title');

        $statuses = Status::all();
        $users = User::where('role', User::ROLE_CONTACT)->get();
        $studyGroups = StudyGroup::query()->orderBy('title')->get();

        $panel = $this->panel();

        return view('admin.biolid.index', compact(
            'bioLids',
            'courses',
            'organizations',
            'statuses',
            'users',
            'studyGroups',
            'panel'
        ));
    }

    public function show(BioLid $biolid)
    {
        $this->authorizeAgentBioLid($biolid);
        $biolid->load(['course', 'status', 'responsible', 'user', 'dataReviewer']);
        $statuses = Status::all();
        $activites = $this->bioLidActivities($biolid, $statuses);

        $docsCollection = UserDocument::query()
            ->with('reviewer')
            ->where('bio_lid_id', $biolid->id)
            ->whereIn('type', array_keys(UserDocument::allBioDocs()))
            ->latest('id')
            ->get();

        $documents = [];
        foreach ($docsCollection as $doc) {
            if (isset($documents[$doc->type])) {
                continue;
            }
            $documents[$doc->type] = $doc;
        }

        return view('admin.biolid.show', [
            'bioLid' => $biolid,
            'statuses' => $statuses,
            'activites' => $activites,
            'needDocs' => UserDocument::BIO_DOCS,
            'applicationDocs' => UserDocument::BIO_APPLICATION_DOCS,
            'consentDocs' => UserDocument::BIO_CONSENT_DOCS,
            'documents' => $documents,
            'reviewStatuses' => UserDocument::REVIEW_STATUSES,
            'panel' => $this->panel(),
        ]);
    }

    public function reviewData(Request $request, BioLid $biolid): JsonResponse|RedirectResponse
    {
        $this->authorizeAgentBioLid($biolid);

        $data = $request->validate([
            'review_status' => ['required', Rule::in(array_keys(UserDocument::REVIEW_STATUSES))],
            'review_comment' => [
                Rule::requiredIf(fn () => $request->input('review_status') === UserDocument::REVIEW_REJECTED),
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'review_comment.required' => 'Укажите причину отклонения',
        ]);

        if (! $biolid->data_submitted_at && $biolid->data_review_status === null) {
            return $this->reviewResponse(
                $request,
                $biolid,
                'Пользователь ещё не отправил данные на проверку',
                error: true
            );
        }

        $oldStatus = $biolid->data_review_status;
        $biolid->forceFill([
            'data_review_status' => $data['review_status'],
            'data_review_comment' => $data['review_status'] === UserDocument::REVIEW_REJECTED
                ? ($data['review_comment'] ?? null)
                : null,
            'data_reviewed_at' => now(),
            'data_reviewed_by' => auth()->id(),
        ])->save();

        $activityComment = sprintf(
            'Проверка данных: %s → %s%s',
            UserDocument::REVIEW_STATUSES[$oldStatus] ?? 'Не отправлено',
            UserDocument::REVIEW_STATUSES[$data['review_status']],
            ! empty($data['review_comment']) ? '. Комментарий: '.$data['review_comment'] : ''
        );

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $biolid->status_id,
                'status_id' => $biolid->status_id,
                'comment' => $activityComment,
            ])
            ->log('Проверка данных заявки');

        $biolid->loadMissing(['dataReviewer', 'status']);

        return $this->reviewResponse(
            $request,
            $biolid,
            'Статус проверки данных обновлён',
            [
                'status' => $biolid->data_review_status,
                'label' => $biolid->dataReviewStatusLabel(),
                'badge' => $biolid->dataReviewStatusBadgeClass(),
                'comment' => $biolid->data_review_comment,
                'reviewed_at' => $biolid->data_reviewed_at?->format('d.m.Y H:i'),
                'reviewer_name' => $biolid->dataReviewer?->name,
            ],
            [
                'updated_at' => now()->format('Y-m-d H:i:s'),
                'user' => auth()->user()?->name ?? '—',
                'description' => 'Проверка данных заявки',
                'status_old' => $biolid->status->title ?? '—',
                'status_new' => $biolid->status->title ?? '—',
                'comment' => $activityComment,
            ]
        );
    }

    public function reviewDocument(Request $request, BioLid $biolid, UserDocument $document): JsonResponse|RedirectResponse
    {
        $this->authorizeAgentBioLid($biolid);

        if ((int) $document->bio_lid_id !== (int) $biolid->id) {
            abort(404);
        }

        if (! isset(UserDocument::allBioDocs()[$document->type])) {
            return $this->reviewResponse(
                $request,
                $biolid,
                'Неизвестный тип документа',
                error: true
            );
        }

        $data = $request->validate([
            'review_status' => ['required', Rule::in(array_keys(UserDocument::REVIEW_STATUSES))],
            'review_comment' => [
                Rule::requiredIf(fn () => $request->input('review_status') === UserDocument::REVIEW_REJECTED),
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'review_comment.required' => 'Укажите причину отклонения',
        ]);

        $oldStatus = $document->review_status;
        $document->update([
            'review_status' => $data['review_status'],
            'review_comment' => $data['review_status'] === UserDocument::REVIEW_REJECTED
                ? ($data['review_comment'] ?? null)
                : null,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        $docTitle = UserDocument::allBioDocs()[$document->type]['title'];
        $activityComment = sprintf(
            'Проверка «%s»: %s → %s%s',
            $docTitle,
            UserDocument::REVIEW_STATUSES[$oldStatus] ?? 'Не отправлено',
            UserDocument::REVIEW_STATUSES[$data['review_status']],
            ! empty($data['review_comment']) ? '. Комментарий: '.$data['review_comment'] : ''
        );

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $biolid->status_id,
                'status_id' => $biolid->status_id,
                'comment' => $activityComment,
                'document_id' => $document->id,
                'document_type' => $document->type,
            ])
            ->log('Проверка документа');

        $document->loadMissing('reviewer');
        $biolid->loadMissing('status');

        return $this->reviewResponse(
            $request,
            $biolid,
            'Статус проверки документа обновлён',
            [
                'status' => $document->review_status,
                'label' => $document->reviewStatusLabel(),
                'badge' => $document->reviewStatusBadgeClass(),
                'comment' => $document->review_comment,
                'reviewed_at' => $document->reviewed_at?->format('d.m.Y H:i'),
                'reviewer_name' => $document->reviewer?->name,
            ],
            [
                'updated_at' => now()->format('Y-m-d H:i:s'),
                'user' => auth()->user()?->name ?? '—',
                'description' => 'Проверка документа',
                'status_old' => $biolid->status->title ?? '—',
                'status_new' => $biolid->status->title ?? '—',
                'comment' => $activityComment,
            ]
        );
    }

    /**
     * @param  array{status?: string, label?: string, badge?: string, comment?: ?string, reviewed_at?: ?string, reviewer_name?: ?string}|null  $review
     * @param  array{updated_at?: string, user?: string, description?: string, status_old?: string, status_new?: string, comment?: string}|null  $activity
     */
    protected function reviewResponse(
        Request $request,
        BioLid $biolid,
        string $message,
        ?array $review = null,
        ?array $activity = null,
        bool $error = false
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'review' => $review,
                'activity' => $activity,
            ], $error ? 422 : 200);
        }

        $redirect = $this->panelRedirect('biolid.show', $biolid);

        return $error
            ? $redirect->with('error', $message)
            : $redirect->with('success', $message);
    }

    public function create()
    {
        $courses = Course::query()
            ->where('bioeconomy', 1)
            ->orderBy('title')
            ->get();

        $organizations = $this->organizationsForPanel();

        $statuses = Status::all();
        $users = User::where('role', User::ROLE_CONTACT)->get();

        return view('admin.biolid.create', [
            'courses' => $courses,
            'organizations' => $organizations,
            'genders' => BioLid::GENDERS,
            'statuses' => $statuses,
            'users' => $users,
            'panel' => $this->panel(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['org_id']);
        $data['politic'] = true;

        $this->assertAgentMayUseOrganizationTitle($data['organization_title'] ?? null);

        $bioLid = BioLid::create($data);
        $course = Course::query()->find($bioLid->course_id);

        if ($course) {
            $this->automation->processAfterCreate($bioLid, $course);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($bioLid)
            ->withProperties([
                'status_id_old' => null,
                'status_id' => $bioLid->status_id,
                'comment' => 'Создание заявки',
            ])
            ->log('Создание заявки');

        return $this->panelRedirect('biolid.show', $bioLid)
            ->with('success', 'Заявка создана');
    }

    public function edit(BioLid $biolid)
    {
        $this->authorizeAgentBioLid($biolid);

        $courses = Course::query()
            ->where('bioeconomy', 1)
            ->orderBy('title')
            ->get();

        $statuses = Status::all();
        $users = User::where('role', User::ROLE_CONTACT)->get();

        return view('admin.biolid.edit', [
            'bioLid' => $biolid,
            'courses' => $courses,
            'genders' => BioLid::GENDERS,
            'statuses' => $statuses,
            'users' => $users,
            'panel' => $this->panel(),
        ]);
    }

    public function update(UpdateRequest $request, BioLid $biolid)
    {
        $this->authorizeAgentBioLid($biolid);

        $data = $request->validated();
        $this->assertAgentMayUseOrganizationTitle($data['organization_title'] ?? null);
        $oldStatus = $biolid->status_id;
        $biolid->update($data);

        $actionDescription = $oldStatus != ($data['status_id'] ?? $oldStatus)
            ? 'Изменение статуса'
            : 'Изменение информации';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $oldStatus,
                'status_id' => $data['status_id'] ?? $oldStatus,
                'comment' => $request->input('comment'),
            ])
            ->log($actionDescription);

        return $this->panelRedirect('biolid.show', $biolid);
    }

    public function destroy(BioLid $biolid)
    {
        $this->authorizeAgentBioLid($biolid);
        $biolid->delete();

        return $this->panelRedirect('biolid.index')
            ->with('success', 'Заявка удалена');
    }

    public function questionnaire(Request $request): BinaryFileResponse
    {
        $ids = $this->filterAgentAccessibleIds($request->input('ids'));

        $filename = 'anketa-gruppy-bio-'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(new BioLidsQuestionnaireExport($ids), $filename);
    }

    public function documentsArchive(Request $request, BioLidDocumentsArchiveService $archiveService): BinaryFileResponse|RedirectResponse
    {
        $ids = $this->filterAgentAccessibleIds($request->input('ids'));

        try {
            $zipPath = $archiveService->create($ids);
        } catch (\Throwable $e) {
            report($e);

            return $this->panelRedirect('biolid.index')
                ->with('error', $e->getMessage() ?: 'Не удалось сформировать архив документов');
        }

        $filename = 'dokumenty-bio-'.now()->format('Y-m-d_H-i-s').'.zip';

        return response()
            ->download($zipPath, $filename, [
                'Content-Type' => 'application/zip',
            ])
            ->deleteFileAfterSend(true);
    }

    public function createUser(BioLid $biolid): RedirectResponse
    {
        $this->authorizeAgentBioLid($biolid);

        if ($biolid->user) {
            return $this->panelRedirect('biolid.show', $biolid)
                ->with('error', 'Учётка уже привязана');
        }

        try {
            $result = $this->automation->ensureUser($biolid);
        } catch (\InvalidArgumentException $e) {
            return $this->panelRedirect('biolid.show', $biolid)
                ->with('error', $e->getMessage());
        }

        $user = $result['user'];
        $created = $result['created'];

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $biolid->status_id,
                'status_id' => $biolid->status_id,
                'comment' => ($created ? 'Создан' : 'Привязан').' пользователь #'.$user->id,
                'user_id' => $user->id,
            ])
            ->log($created ? 'Создание учётной записи' : 'Привязка учётной записи');

        return $this->panelRedirect('biolid.show', $biolid)
            ->with(
                'success',
                $created
                    ? 'Учётная запись создана и привязана к заявке'
                    : 'Существующая учётная запись привязана к заявке'
            );
    }

    public function sendCredentials(BioLid $biolid): RedirectResponse
    {
        $this->authorizeAgentBioLid($biolid);
        $biolid->loadMissing('user');

        if (! $biolid->user) {
            return $this->panelRedirect('biolid.show', $biolid)
                ->with('error', 'Сначала создайте или привяжите учётную запись');
        }

        try {
            $this->automation->sendCredentialsEmail($biolid);
        } catch (\Throwable $e) {
            report($e);

            return $this->panelRedirect('biolid.show', $biolid)
                ->with('error', 'Не удалось отправить письмо. Пароль обновлён, попробуйте ещё раз.');
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $biolid->status_id,
                'status_id' => $biolid->status_id,
                'comment' => 'Отправлено письмо с доступом на '.$biolid->user->email,
                'user_id' => $biolid->user->id,
            ])
            ->log('Отправка письма с доступом');

        return $this->panelRedirect('biolid.show', $biolid)
            ->with('success', 'Письмо с доступом отправлено');
    }

    public function sendCredentialsBulk(Request $request): RedirectResponse
    {
        $ids = $this->filterAgentAccessibleIds($request->input('ids'));

        if ($ids === []) {
            return $this->panelRedirect('biolid.index')
                ->with('error', 'Нет заявок для отправки по текущим фильтрам');
        }

        $bioLids = BioLid::query()
            ->with('user')
            ->whereIn('id', $ids)
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($bioLids as $biolid) {
            try {
                $result = $this->automation->ensureUser($biolid);
                $password = $result['password'];

                if ($password === null) {
                    $this->automation->sendCredentialsEmail($biolid);
                } else {
                    $this->automation->sendCredentialsEmail($biolid, $password);
                }

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($biolid)
                    ->withProperties([
                        'status_id_old' => $biolid->status_id,
                        'status_id' => $biolid->status_id,
                        'comment' => 'Массовая отправка письма с доступом на '.$biolid->user->email,
                        'user_id' => $biolid->user->id,
                    ])
                    ->log('Отправка письма с доступом');

                $sent++;
            } catch (\Throwable $e) {
                report($e);
                $failed++;
            }
        }

        if ($sent === 0) {
            return $this->panelRedirect('biolid.index')
                ->with('error', 'Не удалось отправить письма (ошибок: '.$failed.')');
        }

        $message = 'Письма с доступом отправлены: '.$sent;
        if ($failed > 0) {
            $message .= '. Не отправлено: '.$failed;
        }

        return $this->panelRedirect('biolid.index')
            ->with('success', $message);
    }

    public function createUsersBulk(Request $request): RedirectResponse
    {
        $ids = $this->filterAgentAccessibleIds($request->input('ids'));

        if ($ids === []) {
            return $this->panelRedirect('biolid.index')
                ->with('error', 'Нет заявок для создания пользователей по текущим фильтрам');
        }

        $studyGroupId = $request->input('study_group_id');
        $studyGroupId = ($studyGroupId === '' || $studyGroupId === null) ? null : (int) $studyGroupId;

        if ($studyGroupId !== null && ! StudyGroup::query()->whereKey($studyGroupId)->exists()) {
            return $this->panelRedirect('biolid.index')
                ->with('error', 'Выбранная учебная группа не найдена');
        }

        $bioLids = BioLid::query()
            ->with('user')
            ->whereIn('id', $ids)
            ->get();

        $created = 0;
        $linked = 0;
        $skipped = 0;
        $enrolled = 0;
        $failed = 0;
        $errorDetails = [];

        foreach ($bioLids as $biolid) {
            try {
                if ($biolid->user) {
                    $user = $biolid->user;
                    $skipped++;
                } else {
                    $result = $this->automation->ensureUser($biolid);
                    $user = $result['user'];

                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($biolid)
                        ->withProperties([
                            'status_id_old' => $biolid->status_id,
                            'status_id' => $biolid->status_id,
                            'comment' => ($result['created'] ? 'Создан' : 'Привязан').' пользователь #'.$user->id.' (массово)',
                            'user_id' => $user->id,
                        ])
                        ->log($result['created'] ? 'Создание учётной записи' : 'Привязка учётной записи');

                    if ($result['created']) {
                        $created++;
                    } else {
                        $linked++;
                    }
                }

                if ($studyGroupId !== null) {
                    $this->automation->attachToStudyGroup($user, $studyGroupId);
                    $enrolled++;
                }
            } catch (\Throwable $e) {
                report($e);
                $failed++;
                $errorDetails[] = $this->formatBioLidBulkError($biolid, $e);
            }
        }

        $parts = [];
        if ($created > 0) {
            $parts[] = 'создано: '.$created;
        }
        if ($linked > 0) {
            $parts[] = 'привязано существующих: '.$linked;
        }
        if ($skipped > 0) {
            $parts[] = 'уже были: '.$skipped;
        }
        if ($enrolled > 0) {
            $parts[] = 'записано в группу: '.$enrolled;
        }
        if ($failed > 0) {
            $parts[] = 'ошибок: '.$failed;
        }

        $summary = $parts === []
            ? 'Не удалось обработать выбранные заявки'
            : 'Учётные записи: '.implode(', ', $parts);

        if ($created === 0 && $linked === 0 && $enrolled === 0 && $skipped > 0 && $failed === 0) {
            $summary = 'Новых учёток нет: у всех выбранных заявок пользователь уже привязан ('.$skipped.')';
        }

        $redirect = $this->panelRedirect('biolid.index');

        if ($failed > 0 && $created === 0 && $linked === 0 && $enrolled === 0) {
            return $redirect
                ->with('error', $summary)
                ->with('error_details', $errorDetails);
        }

        if ($failed > 0) {
            return $redirect
                ->with('success', $summary)
                ->with('error_details', $errorDetails);
        }

        return $redirect->with('success', $summary);
    }

    protected function authorizeAgentBioLid(BioLid $biolid): void
    {
        if ($this->panel() !== 'agent') {
            return;
        }

        $user = auth()->user();
        if (! $user instanceof User || ! $user->canAccessBioLid($biolid)) {
            abort(404);
        }
    }

    protected function applyAgentOrganizationScope($query): void
    {
        if ($this->panel() !== 'agent') {
            return;
        }

        $user = auth()->user();
        if (! $user instanceof User) {
            $query->whereRaw('1 = 0');

            return;
        }

        $titles = $user->bioOrganizationTitles();
        if ($titles === []) {
            $query->whereRaw('1 = 0');

            return;
        }

        $query->whereIn('organization_title', $titles);
    }

    /**
     * @return \Illuminate\Support\Collection<int, Org>
     */
    protected function organizationsForPanel()
    {
        $organizations = Org::publishedBioeconomyOptions();

        if ($this->panel() !== 'agent') {
            return $organizations;
        }

        $user = auth()->user();
        if (! $user instanceof User) {
            return collect();
        }

        $allowedTitles = collect($user->bioOrganizationTitles())
            ->map(static fn (string $title) => mb_strtolower($title))
            ->all();

        if ($allowedTitles === []) {
            return collect();
        }

        return $organizations
            ->filter(static function (Org $org) use ($allowedTitles) {
                return in_array(mb_strtolower(trim($org->organization_title)), $allowedTitles, true);
            })
            ->values();
    }

    protected function assertAgentMayUseOrganizationTitle(?string $organizationTitle): void
    {
        if ($this->panel() !== 'agent') {
            return;
        }

        $user = auth()->user();
        if (! $user instanceof User) {
            abort(403);
        }

        $title = trim((string) $organizationTitle);
        if ($title === '' || ! in_array($title, $user->bioOrganizationTitles(), true)) {
            abort(403, 'Организация недоступна для агента');
        }
    }

    /**
     * @param  mixed  $ids
     * @return list<int>
     */
    protected function filterAgentAccessibleIds($ids): array
    {
        if (! is_array($ids)) {
            $ids = [];
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if ($ids === [] || $this->panel() !== 'agent') {
            return $ids;
        }

        $query = BioLid::query()->whereIn('id', $ids);
        $this->applyAgentOrganizationScope($query);

        return $query->pluck('id')->map(static fn ($id) => (int) $id)->all();
    }

    protected function formatBioLidBulkError(BioLid $biolid, \Throwable $e): string
    {
        $label = 'Заявка #'.$biolid->id;
        $email = trim((string) $biolid->email);
        if ($email !== '') {
            $label .= ' ('.$email.')';
        }

        $fio = trim(implode(' ', array_filter([
            $biolid->lastname,
            $biolid->firstname,
            $biolid->middlename,
        ])));
        if ($fio !== '') {
            $label .= ', '.$fio;
        }

        $reason = trim($e->getMessage());
        if ($reason === '') {
            $reason = 'неизвестная ошибка';
        }

        if ($e instanceof \Illuminate\Database\QueryException) {
            $sqlMessage = $e->getMessage();
            if (
                str_contains($sqlMessage, 'Duplicate')
                || str_contains($sqlMessage, '1062')
                || str_contains($sqlMessage, 'UNIQUE')
            ) {
                $reason = 'email уже используется другой учётной записью';
            } else {
                $reason = 'ошибка сохранения в базе данных';
            }
        }

        return $label.': '.$reason;
    }

    protected function bioLidActivities(BioLid $bioLid, Collection $statuses): Collection
    {
        $activites = Activity::query()
            ->where('subject_type', BioLid::class)
            ->where('subject_id', $bioLid->id)
            ->orderBy('created_at')
            ->get();

        $activites->each(function ($item) use ($statuses) {
            $properties = $item->properties;
            $oldStatus = $statuses->where('id', $properties['status_id_old'] ?? null)->first();
            $newStatus = $statuses->where('id', $properties['status_id'] ?? null)->first();
            $item->status_old = $oldStatus->title ?? '—';
            $item->status_new = $newStatus->title ?? '—';
            $item->comment = $properties['comment'] ?? '';
            $item->user = User::find($item->causer_id)?->name ?? '—';
        });

        $firstActivity = $activites->firstWhere('description', 'Изменение статуса');
        if ($firstActivity) {
            $interval = date_diff($firstActivity->created_at, $bioLid->created_at);
            $formatStr = '%hч %iмин';
            if ($interval->d > 0) {
                $formatStr = '%dд '.$formatStr;
            }
            if ($interval->m > 0) {
                $formatStr = '%mм '.$formatStr;
            }
            if ($interval->y > 0) {
                $formatStr = '%yг '.$formatStr;
            }
            $activites->interval = $interval->format($formatStr);
        } else {
            $activites->interval = '---';
        }

        return $activites;
    }
}
