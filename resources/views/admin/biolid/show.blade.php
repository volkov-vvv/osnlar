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

        $historyCount = $activites->count();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <h1 class="m-0 d-inline-flex flex-wrap align-items-center">
                        <span class="mr-2">Заявка №{{ $bioLid->id }} — {{ $fio }}</span>
                        @if($bioLid->status)
                            <span class="badge badge-lg biolid-status-badge align-middle" style="background-color: {{ $bioLid->status->color }} !important; color: {{ contrast_color($bioLid->status->color) }}; font-size: 0.95rem; font-weight: 500;">
                                {{ $bioLid->status->title }}
                            </span>
                        @else
                            <span class="badge badge-secondary biolid-status-badge align-middle">Без статуса</span>
                        @endif
                    </h1>
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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- 1. Действия --}}
            <div class="mb-3">
                <a class="btn btn-outline-secondary" href="{{ route($panel . '.biolid.index') }}">Назад</a>
                <a class="btn btn-primary" href="{{ route($panel . '.biolid.edit', $bioLid->id) }}">Редактировать</a>
                <form method="post" action="{{ route($panel . '.biolid.destroy', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Удалить заявку?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Удалить</button>
                </form>
            </div>

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
                        @foreach($docSummary as $item)
                            <span class="mr-3 mb-1">
                                <span class="text-muted">{{ $item['title'] }}:</span>
                                <span class="{{ $item['class'] }}">{{ $item['label'] }}</span>
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
                            <th style="min-width: 280px;">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1. Данные заявки по БИО</td>
                            <td>
                                @if($bioLid->data_submitted_at || $bioLid->data_review_status)
                                    Данные из заявки
                                    @if($bioLid->isDataRejected())
                                        @if($bioLid->data_reviewed_at)
                                            <div class="small text-danger mt-1">Дата отклонения: {{ $bioLid->data_reviewed_at->format('d.m.Y H:i') }}</div>
                                        @endif
                                        @if($bioLid->data_review_comment)
                                            <div class="small text-danger mt-1">Причина: {{ $bioLid->data_review_comment }}</div>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-muted">Ещё не отправлено</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $bioLid->dataReviewStatusBadgeClass() }}">{{ $bioLid->dataReviewStatusLabel() }}</span>
                            </td>
                            <td>{{ $bioLid->data_submitted_at?->format('d.m.Y H:i') ?: '—' }}</td>
                            <td>
                                @if($bioLid->data_reviewed_at)
                                    {{ $bioLid->data_reviewed_at->format('d.m.Y H:i') }}
                                    @if($bioLid->dataReviewer)
                                        <div class="small text-muted">{{ $bioLid->dataReviewer->name }}</div>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
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
                        </tr>

                        @php $step = 1; @endphp
                        @foreach($needDocs as $type => $needDoc)
                            @php
                                $step++;
                                $document = $documents[$type] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $step }}. {{ $needDoc['title'] }}</td>
                                <td>
                                    @if($document && $document->file)
                                        <a href="{{ url('storage/'.$document->file) }}" target="_blank" rel="noopener">
                                            {{ $document->title }}
                                        </a>
                                        @if($document->isRejected())
                                            @if($document->reviewed_at)
                                                <div class="small text-danger mt-1">Дата отклонения: {{ $document->reviewed_at->format('d.m.Y H:i') }}</div>
                                            @endif
                                            @if($document->review_comment)
                                                <div class="small text-danger mt-1">Причина: {{ $document->review_comment }}</div>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-muted">Файл не загружен</span>
                                    @endif
                                </td>
                                <td>
                                    @if($document)
                                        <span class="{{ $document->reviewStatusBadgeClass() }}">{{ $document->reviewStatusLabel() }}</span>
                                    @else
                                        <span class="badge badge-secondary">Не отправлено</span>
                                    @endif
                                </td>
                                <td>{{ $document?->submitted_at?->format('d.m.Y H:i') ?: '—' }}</td>
                                <td>
                                    @if($document?->reviewed_at)
                                        {{ $document->reviewed_at->format('d.m.Y H:i') }}
                                        @if($document->reviewer)
                                            <div class="small text-muted">{{ $document->reviewer->name }}</div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
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
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 5. История — развёрнута --}}
            <div class="card card-outline card-secondary mb-4">
                <div class="card-header p-3">
                    <h3 class="card-title mb-0">История изменений ({{ $historyCount }})</h3>
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
                            <th>Изменение</th>
                            <th>Комментарий</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($activites as $activity)
                            <tr>
                                <td>{{ $activity->updated_at }}</td>
                                <td>{{ $activity->user }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->status_old }} <i class="fas fa-arrow-right"></i> {{ $activity->status_new }}</td>
                                <td class="text-wrap">{{ $activity->comment ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Нет изменений</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        document.querySelectorAll('.biolid-review-form').forEach(function (form) {
            var select = form.querySelector('.review-status-select');
            var commentWrap = form.querySelector('.review-comment-wrap');
            if (!select || !commentWrap) {
                return;
            }

            function syncComment() {
                commentWrap.style.display = select.value === 'rejected' ? '' : 'none';
            }

            select.addEventListener('change', syncComment);
            syncComment();
        });
    </script>
@endsection
