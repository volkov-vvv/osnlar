@extends($panel . '.layouts.main')
@section('content')
    <style>
        .biolid-status-badge {
            border-radius: 999px;
            padding: 0.4rem 0.9rem !important;
        }

        .biolid-show .card {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .biolid-show .card-header {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .biolid-show .biolid-data-card > .card-header {
            border-bottom: 0 !important;
            background-color: #fff;
        }

        .biolid-show .biolid-data-card .table > tbody > tr:first-child > td {
            border-top: 0;
        }

        .biolid-show .badge:not(.biolid-status-badge) {
            border-radius: 0.5rem;
        }

        .biolid-arpm-comment-box {
            max-width: 720px;
        }
    </style>
    @php
        $fio = trim(implode(' ', array_filter([
            $bioLid->lastname,
            $bioLid->firstname,
            $bioLid->middlename,
        ]))) ?: 'Без ФИО';

        $docSummary = [];
        $docSummary[] = [
            'title' => 'Данные',
            'label' => $bioLid->dataReviewStatusLabel(),
            'class' => $bioLid->dataReviewStatusBadgeClass(),
        ];
        foreach ($needDocs as $type => $needDoc) {
            $document = $documents[$type] ?? null;
            $shortTitle = match ($type) {
                'passport' => 'Паспорт',
                'education_doc' => 'Образование',
                'inn' => 'ИНН',
                'snils' => 'СНИЛС',
                default => $needDoc['title'],
            };
            $docSummary[] = [
                'title' => $shortTitle,
                'label' => $document ? $document->reviewStatusLabel() : 'Не отправлено',
                'class' => $document ? $document->reviewStatusBadgeClass() : 'badge badge-secondary',
            ];
        }
        foreach ($applicationDocs as $type => $needDoc) {
            $document = $documents[$type] ?? null;
            $docSummary[] = [
                'title' => match ($type) {
                    'application' => 'Заявление',
                    'name_change_doc' => 'Смена фамилии',
                    default => $needDoc['title'],
                },
                'label' => $document
                    ? $document->reviewStatusLabel()
                    : (! empty($needDoc['optional']) ? 'Необязательно' : 'Не отправлено'),
                'class' => $document
                    ? $document->reviewStatusBadgeClass()
                    : 'badge badge-secondary',
            ];
        }
        foreach ($consentDocs as $type => $needDoc) {
            $document = $documents[$type] ?? null;
            $docSummary[] = [
                'title' => match ($type) {
                    'consent_arpm' => 'Согл. АРПМ',
                    'consent_osnovanie' => 'Согл. Основание',
                    default => $needDoc['title'],
                },
                'label' => $document ? $document->reviewStatusLabel() : 'Не отправлено',
                'class' => $document ? $document->reviewStatusBadgeClass() : 'badge badge-secondary',
            ];
        }

        $historyCount = $activites->count();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <h1 class="m-0">Заявка №{{ $bioLid->id }} — {{ $fio }}</h1>
                </div>
            </div>
            <div class="text-muted mb-2">
                <span class="mr-3">
                    <strong>Курс:</strong>
                    @if($bioLid->course)
                        @if($bioLid->course->short_title)
                            {{ $bioLid->course->short_title }} — {{ $bioLid->course->title }}
                        @else
                            {{ $bioLid->course->title }}
                        @endif
                    @else
                        —
                    @endif
                </span>
                <span class="mr-3"><strong>Ответственный:</strong> {{ $bioLid->responsible->name ?? '—' }}</span>
                <span class="mr-3"><strong>Первая реакция:</strong> {{ $activites->interval }}</span>
                <span class="mr-3"><strong>Создано:</strong> {{ $bioLid->created_at }}</span>
                <span class="mr-3"><strong>Обновлено:</strong> {{ $bioLid->updated_at }}</span>
            </div>
        </div>
    </div>

    <section class="content biolid-show">
        <div class="container-fluid">
            <div id="biolid-review-alerts">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
            </div>

            {{-- 1. Действия --}}
            <div class="mb-3">
                <a class="btn btn-outline-secondary" href="{{ route($panel . '.biolid.index') }}">Назад</a>
                @if($panel !== 'agent')
                    <a class="btn btn-primary" href="{{ route($panel . '.biolid.edit', $bioLid->id) }}">Редактировать</a>
                    <form method="post" action="{{ route($panel . '.biolid.destroy', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Удалить заявку?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Удалить</button>
                    </form>
                @endif
            </div>

            @if($panel !== 'agent')
                {{-- 2. Учётная запись --}}
                <div class="card card-outline card-info mb-3">
                    <div class="card-header p-3">
                        <h3 class="card-title mb-0">Учётная запись</h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="mb-2 mb-md-0">
                                @if($bioLid->user)
                                    <span class="badge badge-success mr-2">Привязана</span>
                                    @if($panel === 'admin')
                                        <a href="{{ route('admin.user.show', $bioLid->user->id) }}">
                                            {{ trim($bioLid->user->lastname.' '.$bioLid->user->name.' '.$bioLid->user->middlename) ?: $bioLid->user->email }}
                                        </a>
                                    @else
                                        <strong>{{ trim($bioLid->user->lastname.' '.$bioLid->user->name.' '.$bioLid->user->middlename) ?: $bioLid->user->email }}</strong>
                                    @endif
                                    <span class="text-muted ml-1">({{ $bioLid->user->email }})</span>
                                @else
                                    <span class="badge badge-secondary mr-2">Нет учётки</span>
                                    <span class="text-muted">Email заявки: {{ $bioLid->email }}</span>
                                @endif
                            </div>
                            <div>
                                @if(!$bioLid->user)
                                    <form method="post" action="{{ route($panel . '.biolid.create-user', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Создать или привязать учётную запись пользователя?');">
                                        @csrf
                                        <button class="btn btn-success btn-sm" type="submit">Создать учётную запись</button>
                                    </form>
                                @else
                                    @if($panel === 'admin')
                                        <a class="btn btn-outline-info btn-sm" href="{{ route('admin.user.show', $bioLid->user->id) }}">Открыть пользователя</a>
                                    @endif
                                    <form method="post" action="{{ route($panel . '.biolid.send-credentials', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Будет сгенерирован новый пароль и отправлен на email пользователя. Продолжить?');">
                                        @csrf
                                        <button class="btn btn-warning btn-sm" type="submit">Отправить письмо</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 3. Данные заявки — две колонки, свёрнуто по умолчанию --}}
            <div class="card card-outline card-primary biolid-data-card collapsed-card mb-3">
                <div class="card-header p-3">
                    <h3 class="card-title mb-0">Данные заявки</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                    <tr>
                                        <td width="40%">Фамилия</td>
                                        <td>{{ $bioLid->lastname }}</td>
                                    </tr>
                                    <tr>
                                        <td>Имя</td>
                                        <td>{{ $bioLid->firstname }}</td>
                                    </tr>
                                    <tr>
                                        <td>Отчество</td>
                                        <td>{{ $bioLid->middlename ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата рождения</td>
                                        <td>{{ $bioLid->birth_date ? $bioLid->birth_date->format('d.m.Y') : '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Пол</td>
                                        <td>{{ $bioLid->genderLabel() ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $bioLid->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Телефон</td>
                                        <td>{{ $bioLid->phoneDisplay() }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                    <tr>
                                        <td width="40%">СНИЛС</td>
                                        <td>{{ $bioLid->snils ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Гражданство</td>
                                        <td>{{ $bioLid->citizenship ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Адрес места жительства</td>
                                        <td>{{ $bioLid->residence_address ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Серия / номер паспорта</td>
                                        <td>
                                            @if($bioLid->passport_series || $bioLid->passport_number)
                                                {{ trim(($bioLid->passport_series ?: '').' '.$bioLid->passport_number) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Кем выдан</td>
                                        <td>{{ $bioLid->passport_issued_by ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата выдачи</td>
                                        <td>{{ $bioLid->passport_issued_at ? $bioLid->passport_issued_at->format('d.m.Y') : '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Код подразделения</td>
                                        <td>{{ $bioLid->passport_department_code ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Организация</td>
                                        <td>{{ $bioLid->organization_title ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>ИНН организации</td>
                                        <td>{{ $bioLid->organization_inn ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Должность</td>
                                        <td>{{ $bioLid->position ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Образование</td>
                                        <td>{{ $bioLid->education ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Образовательная организация</td>
                                        <td>{{ $bioLid->education_organization ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Полученная квалификация</td>
                                        <td>{{ $bioLid->education_qualification ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Серия и номер диплома</td>
                                        <td>{{ $bioLid->diploma_series_number ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата выдачи диплома</td>
                                        <td>{{ $bioLid->diploma_issued_at ? $bioLid->diploma_issued_at->format('d.m.Y') : '—' }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Документы и проверка: статусы всегда видны, таблица свёрнута по умолчанию --}}
            <div class="card card-outline card-secondary collapsed-card mb-3">
                <div class="card-header p-3 flex-wrap">
                    <h3 class="card-title mb-0">Документы и проверка</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="w-100 pt-3 mt-2 d-flex flex-wrap" style="flex-basis: 100%;">
                        @foreach($docSummary as $index => $item)
                            <span class="mr-3 mb-1" data-summary-index="{{ $index }}">
                                <span class="text-muted">{{ $item['title'] }}:</span>
                                <span class="doc-summary-badge {{ $item['class'] }}">{{ $item['label'] }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Пункт</th>
                            <th>Файл / данные</th>
                            <th>Статус</th>
                            <th>Отправлено</th>
                            <th>Проверено</th>
                            @if($panel !== 'agent')
                                <th style="min-width: 280px;">Действия</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-summary-index="0">
                            <td>1. Данные заявки по БИО</td>
                            <td class="review-file-cell">
                                @if($bioLid->data_submitted_at || $bioLid->data_review_status)
                                    Данные из заявки
                                    <div class="review-rejection-info">
                                        @if($bioLid->isDataRejected())
                                            @if($bioLid->data_reviewed_at)
                                                <div class="small text-danger mt-1">Дата отклонения: {{ $bioLid->data_reviewed_at->format('d.m.Y H:i') }}</div>
                                            @endif
                                            @if($bioLid->data_review_comment)
                                                <div class="small text-danger mt-1">Причина: {{ $bioLid->data_review_comment }}</div>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">Ещё не отправлено</span>
                                @endif
                            </td>
                            <td class="review-status-cell">
                                <span class="{{ $bioLid->dataReviewStatusBadgeClass() }}">{{ $bioLid->dataReviewStatusLabel() }}</span>
                            </td>
                            <td>{{ $bioLid->data_submitted_at?->format('d.m.Y H:i') ?: '—' }}</td>
                            <td class="review-checked-cell">
                                @if($bioLid->data_reviewed_at)
                                    {{ $bioLid->data_reviewed_at->format('d.m.Y H:i') }}
                                    @if($bioLid->dataReviewer)
                                        <div class="small text-muted">{{ $bioLid->dataReviewer->name }}</div>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            @if($panel !== 'agent')
                                <td>
                                    @if($bioLid->data_submitted_at || $bioLid->data_review_status)
                                        <form method="post" action="{{ route($panel . '.biolid.review-data', $bioLid) }}" class="biolid-review-form">
                                            @csrf
                                            <div class="form-row align-items-start">
                                                <div class="col-auto mb-1">
                                                    <select name="review_status" class="form-control form-control-sm review-status-select" required>
                                                        @foreach($reviewStatuses as $value => $label)
                                                            <option value="{{ $value }}" {{ $bioLid->data_review_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col mb-1 review-comment-wrap" style="{{ $bioLid->isDataRejected() ? '' : 'display:none;' }}">
                                                    <input type="text" name="review_comment" class="form-control form-control-sm" placeholder="Причина отклонения" value="{{ old('review_comment', $bioLid->data_review_comment) }}">
                                                </div>
                                                <div class="col-auto mb-1">
                                                    <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            @endif
                        </tr>

                        @php
                            $step = 1;
                            $reviewDocRows = [];
                            foreach ($needDocs as $type => $needDoc) {
                                $step++;
                                $reviewDocRows[] = ['step' => $step, 'type' => $type, 'title' => $needDoc['title']];
                            }
                            $applicationStep = $step + 1;
                            foreach ($applicationDocs as $type => $needDoc) {
                                $title = $needDoc['title'];
                                if (! empty($needDoc['optional'])) {
                                    $title .= ' (при необходимости)';
                                }
                                $reviewDocRows[] = ['step' => $applicationStep, 'type' => $type, 'title' => $title];
                            }
                            $consentStep = $applicationStep + 1;
                            foreach ($consentDocs as $type => $needDoc) {
                                $reviewDocRows[] = ['step' => $consentStep, 'type' => $type, 'title' => $needDoc['title']];
                            }
                        @endphp
                        @foreach($reviewDocRows as $index => $row)
                            @php
                                $document = $documents[$row['type']] ?? null;
                                $docMeta = \App\Models\UserDocument::allBioDocs()[$row['type']] ?? [];
                                $docMimes = $docMeta['mimes'] ?? 'pdf,jpg,jpeg,png';
                            @endphp
                            <tr data-summary-index="{{ $index + 1 }}">
                                <td>{{ $row['step'] }}. {{ $row['title'] }}</td>
                                <td class="review-file-cell">
                                    <div class="d-inline-flex align-items-center flex-wrap">
                                        @if($document && $document->file)
                                            <a href="{{ url('storage/'.$document->file) }}" target="_blank" rel="noopener">
                                                {{ $document->title }}
                                            </a>
                                        @else
                                            <span class="text-muted">Файл не загружен</span>
                                        @endif
                                        @if($panel === 'admin')
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary rounded-circle ml-2 biolid-doc-upload-btn"
                                                title="Загрузить / заменить"
                                                style="width: 2rem; height: 2rem; padding: 0; line-height: 2rem;"
                                                data-type="{{ $row['type'] }}"
                                                data-title="{{ $row['title'] }}"
                                                data-has-file="{{ $document && $document->file ? 1 : 0 }}"
                                                data-file-url="{{ $document && $document->file ? url('storage/'.$document->file) : '' }}"
                                                data-file-name="{{ $document && $document->file ? $document->title : '' }}"
                                                data-upload-url="{{ route('admin.biolid.upload-document', [$bioLid, $row['type']]) }}"
                                                data-mimes="{{ $docMimes }}"
                                            >
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @if($document && $document->file)
                                        <div class="review-rejection-info">
                                            @if($document->isRejected())
                                                @if($document->reviewed_at)
                                                    <div class="small text-danger mt-1">Дата отклонения: {{ $document->reviewed_at->format('d.m.Y H:i') }}</div>
                                                @endif
                                                @if($document->review_comment)
                                                    <div class="small text-danger mt-1">Причина: {{ $document->review_comment }}</div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="review-status-cell">
                                    @if($document)
                                        <span class="{{ $document->reviewStatusBadgeClass() }}">{{ $document->reviewStatusLabel() }}</span>
                                    @else
                                        <span class="badge badge-secondary">Не отправлено</span>
                                    @endif
                                </td>
                                <td>{{ $document?->submitted_at?->format('d.m.Y H:i') ?: '—' }}</td>
                                <td class="review-checked-cell">
                                    @if($document?->reviewed_at)
                                        {{ $document->reviewed_at->format('d.m.Y H:i') }}
                                        @if($document->reviewer)
                                            <div class="small text-muted">{{ $document->reviewer->name }}</div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                @if($panel !== 'agent')
                                    <td>
                                        @if($document)
                                            <form method="post" action="{{ route($panel . '.biolid.review-document', [$bioLid, $document]) }}" class="biolid-review-form">
                                                @csrf
                                                <div class="form-row align-items-start">
                                                    <div class="col-auto mb-1">
                                                        <select name="review_status" class="form-control form-control-sm review-status-select" required>
                                                            @foreach($reviewStatuses as $value => $label)
                                                                <option value="{{ $value }}" {{ $document->review_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col mb-1 review-comment-wrap" style="{{ $document->isRejected() ? '' : 'display:none;' }}">
                                                        <input type="text" name="review_comment" class="form-control form-control-sm" placeholder="Причина отклонения" value="{{ old('review_comment', $document->review_comment) }}">
                                                    </div>
                                                    <div class="col-auto mb-1">
                                                        <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 5. Статус АРПМ --}}
            <div class="card card-outline card-warning mb-3" id="biolid-arpm-status-wrap">
                <div class="card-header p-3">
                    <h3 class="card-title mb-0">
                        Статус АРПМ
                        <span
                            id="biolid-arpm-status-badge"
                            class="{{ $bioLid->arpmStatusBadgeClass() }} ml-2"
                        ><i class="{{ $bioLid->arpmStatusIconClass() }} mr-1" aria-hidden="true"></i>{{ $bioLid->arpmStatusLabel() }}</span>
                    </h3>
                </div>
                <div class="card-body p-3">
                    @if($canManageArpmStatus)
                        <form
                            method="post"
                            action="{{ route($panel . '.biolid.arpm-status', $bioLid) }}"
                            id="biolid-arpm-status-form"
                        >
                            @csrf
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-4 mb-2 mb-md-0">
                                    <label for="biolid-arpm-status-select">Статус</label>
                                    <select
                                        name="arpm_status"
                                        id="biolid-arpm-status-select"
                                        class="form-control select2-arpm-status"
                                        required
                                    >
                                        @foreach($arpmStatuses as $value => $label)
                                            <option
                                                value="{{ $value }}"
                                                data-icon="{{ \App\Models\BioLid::arpmStatusIconClassFor($value) }}"
                                                {{ $bioLid->arpmStatusValue() === $value ? 'selected' : '' }}
                                            >
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-auto mb-2 mb-md-0">
                                    <button type="submit" class="btn btn-primary" id="biolid-arpm-save">
                                        Сохранить
                                    </button>
                                </div>
                            </div>

                            <div
                                id="biolid-arpm-comment-wrap"
                                class="biolid-arpm-comment-box mt-3"
                                style="{{ $bioLid->arpmStatusValue() === \App\Models\BioLid::ARPM_REMARKS ? '' : 'display:none;' }}"
                            >
                                <label for="biolid-arpm-comment">Текст замечаний</label>
                                <textarea
                                    name="arpm_comment"
                                    id="biolid-arpm-comment"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Опишите замечание"
                                >{{ old('arpm_comment', $bioLid->arpm_comment) }}</textarea>
                            </div>
                        </form>
                    @else
                        @if($bioLid->arpmStatusValue() === \App\Models\BioLid::ARPM_REMARKS && $bioLid->arpm_comment)
                            <div class="text-muted" id="biolid-arpm-comment-readonly">
                                {{ $bioLid->arpm_comment }}
                            </div>
                        @else
                            <div class="text-muted">Изменение статуса доступно администраторам и агентам.</div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- 6. История — развёрнута --}}
            <div class="card card-outline card-secondary mb-4">
                <div class="card-header p-3">
                    <h3 class="card-title mb-0">История изменений (<span id="biolid-history-count">{{ $historyCount }}</span>)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Автор</th>
                            <th>Что изменилось</th>
                            <th>Комментарий</th>
                        </tr>
                        </thead>
                        <tbody id="biolid-history-body">
                        @forelse ($activites as $activity)
                            <tr>
                                <td>{{ $activity->updated_at }}</td>
                                <td>{{ $activity->user }}</td>
                                <td>{{ $activity->description }}</td>
                                <td class="text-wrap">{{ $activity->comment ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr class="biolid-history-empty">
                                <td colspan="4">Нет изменений</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @if($panel === 'admin')
        <style>
            #biolidDocUploadModal .modal-content {
                border: 0;
                border-radius: .4rem;
                box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, .18);
                overflow: hidden;
            }
            #biolidDocUploadModal .modal-header {
                align-items: center;
                background: #094ca1;
                border-bottom: 0;
                color: #fff;
                padding: .9rem 1.15rem;
            }
            #biolidDocUploadModal .modal-title {
                font-size: 1.1rem;
                font-weight: 600;
                margin: 0;
            }
            #biolidDocUploadModal .modal-header .close {
                color: #fff;
                opacity: .85;
                text-shadow: none;
            }
            #biolidDocUploadModal .modal-header .close:hover {
                opacity: 1;
            }
            #biolidDocUploadModal .modal-body {
                background: #f4f6f9;
                padding: 1rem 1.15rem;
            }
            #biolidDocUploadModal .modal-footer {
                background: #fff;
                border-top: 1px solid #e3e6ea;
                padding: .85rem 1.15rem;
            }
        </style>

        <div class="modal fade" id="biolidDocUploadModal" tabindex="-1" role="dialog" aria-labelledby="biolidDocUploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="biolidDocUploadForm" method="post" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title" id="biolidDocUploadModalLabel">
                                <i class="fas fa-file-upload mr-2"></i><span id="biolidDocUploadModalTitleText">Загрузить документ</span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-2">
                                <strong id="biolidDocUploadDocName"></strong>
                            </p>
                            <div id="biolidDocUploadCurrentWrap" class="mb-3" style="display: none;">
                                <div class="text-muted small mb-1">Текущий файл</div>
                                <a id="biolidDocUploadCurrentLink" href="#" target="_blank" rel="noopener"></a>
                            </div>
                            <div class="form-group mb-2">
                                <label for="biolidDocUploadInput">Файл</label>
                                <input
                                    type="file"
                                    name="doc"
                                    id="biolidDocUploadInput"
                                    class="form-control-file"
                                    required
                                >
                                <div class="form-text text-muted" id="biolidDocUploadHint">PDF, JPG, PNG. До 5 МБ.</div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary" id="biolidDocUploadSubmit">
                                <i class="fas fa-check mr-1"></i>Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('javascript')
    <script>
        (function () {
            function escapeHtml(value) {
                return String(value == null ? '' : value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function showReviewAlert(message, type) {
                type = type || 'success';
                var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                var container = document.getElementById('biolid-review-alerts');
                if (!container) {
                    return;
                }

                container.innerHTML =
                    '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                        escapeHtml(message) +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                    '</div>';
            }

            function syncCommentVisibility(form) {
                var select = form.querySelector('.review-status-select');
                var commentWrap = form.querySelector('.review-comment-wrap');
                if (!select || !commentWrap) {
                    return;
                }
                commentWrap.style.display = select.value === 'rejected' ? '' : 'none';
            }

            function updateRejectionInfo(row, review) {
                var info = row.querySelector('.review-rejection-info');
                if (!info) {
                    return;
                }

                if (review.status !== 'rejected') {
                    info.innerHTML = '';
                    return;
                }

                var html = '';
                if (review.reviewed_at) {
                    html += '<div class="small text-danger mt-1">Дата отклонения: ' + escapeHtml(review.reviewed_at) + '</div>';
                }
                if (review.comment) {
                    html += '<div class="small text-danger mt-1">Причина: ' + escapeHtml(review.comment) + '</div>';
                }
                info.innerHTML = html;
            }

            function updateRow(form, review) {
                var row = form.closest('tr');
                if (!row || !review) {
                    return;
                }

                var statusCell = row.querySelector('.review-status-cell');
                if (statusCell) {
                    statusCell.innerHTML = '<span class="' + escapeHtml(review.badge) + '">' + escapeHtml(review.label) + '</span>';
                }

                var checkedCell = row.querySelector('.review-checked-cell');
                if (checkedCell) {
                    if (review.reviewed_at) {
                        checkedCell.innerHTML = escapeHtml(review.reviewed_at) +
                            (review.reviewer_name
                                ? '<div class="small text-muted">' + escapeHtml(review.reviewer_name) + '</div>'
                                : '');
                    } else {
                        checkedCell.textContent = '—';
                    }
                }

                updateRejectionInfo(row, review);

                var commentInput = form.querySelector('input[name="review_comment"]');
                if (commentInput) {
                    commentInput.value = review.status === 'rejected' ? (review.comment || '') : '';
                }
                syncCommentVisibility(form);

                var summaryIndex = row.getAttribute('data-summary-index');
                if (summaryIndex !== null) {
                    var summaryBadge = document.querySelector(
                        '[data-summary-index="' + summaryIndex + '"] .doc-summary-badge'
                    );
                    if (summaryBadge) {
                        summaryBadge.className = 'doc-summary-badge ' + (review.badge || '');
                        summaryBadge.textContent = review.label || '';
                    }
                }
            }

            function prependHistory(activity) {
                if (!activity) {
                    return;
                }

                var body = document.getElementById('biolid-history-body');
                var countEl = document.getElementById('biolid-history-count');
                if (!body) {
                    return;
                }

                var emptyRow = body.querySelector('.biolid-history-empty');
                if (emptyRow) {
                    emptyRow.remove();
                }

                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' + escapeHtml(activity.updated_at) + '</td>' +
                    '<td>' + escapeHtml(activity.user) + '</td>' +
                    '<td>' + escapeHtml(activity.description) + '</td>' +
                    '<td class="text-wrap">' + escapeHtml(activity.comment || '—') + '</td>';
                body.insertBefore(tr, body.firstChild);

                if (countEl) {
                    countEl.textContent = String((parseInt(countEl.textContent, 10) || 0) + 1);
                }
            }

            document.querySelectorAll('.biolid-review-form').forEach(function (form) {
                var select = form.querySelector('.review-status-select');
                if (select) {
                    select.addEventListener('change', function () {
                        syncCommentVisibility(form);
                    });
                    syncCommentVisibility(form);
                }

                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    var submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                    }

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form),
                        credentials: 'same-origin'
                    }).then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, status: response.status, data: data };
                        }).catch(function () {
                            return { ok: response.ok, status: response.status, data: null };
                        });
                    }).then(function (result) {
                        if (!result.ok) {
                            var message = (result.data && result.data.message)
                                || (result.data && result.data.errors && Object.values(result.data.errors)[0] && Object.values(result.data.errors)[0][0])
                                || 'Не удалось сохранить статус';
                            showReviewAlert(message, 'error');
                            return;
                        }

                        updateRow(form, result.data.review);
                        prependHistory(result.data.activity);
                        updateArpmStatus(result.data.arpm);
                        showReviewAlert(result.data.message || 'Статус обновлён', 'success');
                    }).catch(function () {
                        showReviewAlert('Не удалось сохранить статус', 'error');
                    }).finally(function () {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                        }
                    });
                });
            });

            var ARPM_REMARKS = @json(\App\Models\BioLid::ARPM_REMARKS);

            function syncArpmCommentVisibility(status) {
                var wrap = document.getElementById('biolid-arpm-comment-wrap');
                if (!wrap) {
                    return;
                }
                wrap.style.display = status === ARPM_REMARKS ? '' : 'none';
            }

            function updateArpmStatus(arpm) {
                if (!arpm) {
                    return;
                }

                var badge = document.getElementById('biolid-arpm-status-badge');
                if (badge) {
                    badge.className = (arpm.badge || 'badge badge-secondary') + ' ml-2';
                    badge.innerHTML = arpm.icon
                        ? '<i class="' + escapeHtml(arpm.icon) + ' mr-1" aria-hidden="true"></i>' + escapeHtml(arpm.label || '')
                        : escapeHtml(arpm.label || '');
                }

                var select = document.getElementById('biolid-arpm-status-select');
                if (select && arpm.status) {
                    select.value = arpm.status;
                    if (window.jQuery && jQuery(select).hasClass('select2-hidden-accessible')) {
                        jQuery(select).trigger('change.select2');
                    }
                    syncArpmCommentVisibility(arpm.status);
                }

                var comment = document.getElementById('biolid-arpm-comment');
                if (comment) {
                    comment.value = arpm.comment || '';
                }

                var readonly = document.getElementById('biolid-arpm-comment-readonly');
                if (readonly) {
                    if (arpm.status === ARPM_REMARKS && arpm.comment) {
                        readonly.style.display = '';
                        readonly.textContent = arpm.comment;
                    } else {
                        readonly.style.display = 'none';
                        readonly.textContent = '';
                    }
                }
            }

            var arpmForm = document.getElementById('biolid-arpm-status-form');
            var arpmSelect = document.getElementById('biolid-arpm-status-select');
            if (arpmSelect) {
                function formatArpmOption(state) {
                    if (!state.id) {
                        return state.text;
                    }
                    var icon = state.element ? state.element.getAttribute('data-icon') : '';
                    if (!icon) {
                        return state.text;
                    }
                    return $('<span><i class="' + icon + ' mr-1" aria-hidden="true"></i>' + state.text + '</span>');
                }

                if (window.jQuery) {
                    jQuery(arpmSelect).select2({
                        width: '100%',
                        minimumResultsForSearch: Infinity,
                        templateResult: formatArpmOption,
                        templateSelection: formatArpmOption,
                        escapeMarkup: function (markup) { return markup; }
                    });

                    jQuery(arpmSelect).on('change', function () {
                        syncArpmCommentVisibility(arpmSelect.value);
                        if (arpmSelect.value === ARPM_REMARKS) {
                            var comment = document.getElementById('biolid-arpm-comment');
                            if (comment) {
                                comment.focus();
                            }
                        }
                    });
                } else {
                    arpmSelect.addEventListener('change', function () {
                        syncArpmCommentVisibility(arpmSelect.value);
                        if (arpmSelect.value === ARPM_REMARKS) {
                            var comment = document.getElementById('biolid-arpm-comment');
                            if (comment) {
                                comment.focus();
                            }
                        }
                    });
                }
            }

            if (arpmForm) {
                arpmForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    var submitBtn = document.getElementById('biolid-arpm-save');
                    var payload = new FormData(arpmForm);
                    if (submitBtn) {
                        submitBtn.disabled = true;
                    }

                    fetch(arpmForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: payload,
                        credentials: 'same-origin'
                    }).then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data };
                        }).catch(function () {
                            return { ok: response.ok, data: null };
                        });
                    }).then(function (result) {
                        if (!result.ok) {
                            var message = (result.data && result.data.message)
                                || (result.data && result.data.errors && Object.values(result.data.errors)[0] && Object.values(result.data.errors)[0][0])
                                || 'Не удалось сохранить статус АРПМ';
                            showReviewAlert(message, 'error');
                            return;
                        }

                        updateArpmStatus(result.data.arpm);
                        prependHistory(result.data.activity);
                        showReviewAlert(result.data.message || 'Статус АРПМ обновлён', 'success');
                    }).catch(function () {
                        showReviewAlert('Не удалось сохранить статус АРПМ', 'error');
                    }).finally(function () {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                        }
                    });
                });
            }

            var uploadModal = document.getElementById('biolidDocUploadModal');
            var uploadForm = document.getElementById('biolidDocUploadForm');
            var uploadInput = document.getElementById('biolidDocUploadInput');
            var uploadTitleText = document.getElementById('biolidDocUploadModalTitleText');
            var uploadDocName = document.getElementById('biolidDocUploadDocName');
            var uploadCurrentWrap = document.getElementById('biolidDocUploadCurrentWrap');
            var uploadCurrentLink = document.getElementById('biolidDocUploadCurrentLink');
            var uploadHint = document.getElementById('biolidDocUploadHint');

            if (uploadModal && uploadForm && typeof $ !== 'undefined') {
                document.querySelectorAll('.biolid-doc-upload-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var hasFile = btn.getAttribute('data-has-file') === '1';
                        var title = btn.getAttribute('data-title') || '';
                        var fileUrl = btn.getAttribute('data-file-url') || '';
                        var fileName = btn.getAttribute('data-file-name') || '';
                        var uploadUrl = btn.getAttribute('data-upload-url') || '#';
                        var mimes = (btn.getAttribute('data-mimes') || 'pdf,jpg,jpeg,png')
                            .split(',')
                            .map(function (item) { return item.trim().toUpperCase(); })
                            .filter(Boolean);

                        uploadForm.action = uploadUrl;
                        uploadTitleText.textContent = hasFile ? 'Заменить документ' : 'Загрузить документ';
                        uploadDocName.textContent = title;
                        uploadHint.textContent = mimes.join(', ') + '. До 5 МБ.';
                        uploadInput.value = '';
                        uploadInput.accept = mimes.map(function (ext) { return '.' + ext.toLowerCase(); }).join(',');

                        if (hasFile && fileUrl) {
                            uploadCurrentWrap.style.display = '';
                            uploadCurrentLink.href = fileUrl;
                            uploadCurrentLink.textContent = fileName || 'Открыть файл';
                        } else {
                            uploadCurrentWrap.style.display = 'none';
                            uploadCurrentLink.href = '#';
                            uploadCurrentLink.textContent = '';
                        }

                        $('#biolidDocUploadModal').modal('show');
                    });
                });

                uploadForm.addEventListener('submit', function () {
                    var submitBtn = document.getElementById('biolidDocUploadSubmit');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                    }
                });
            }
        })();
    </script>
@endsection
