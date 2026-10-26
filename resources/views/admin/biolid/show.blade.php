@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заявка по БИО №{{ $bioLid->id }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-xl-6">
                    @if($bioLid->status)
                        <div class="alert" style="background-color: {{ $bioLid->status->color }} !important; color: {{ contrast_color($bioLid->status->color) }}">
                            {{ $bioLid->status->title }}
                        </div>
                    @else
                        <div class="alert alert-secondary">—</div>
                    @endif

                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td width="40%">Номер заявки</td>
                                    <td>{{ $bioLid->id }}</td>
                                </tr>
                                <tr>
                                    <td>Время первой реакции</td>
                                    <td>{{ $activites->interval }}</td>
                                </tr>
                                <tr>
                                    <td>Ответственный</td>
                                    <td>{{ $bioLid->responsible->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Пользователь</td>
                                    <td>
                                        @if($bioLid->user)
                                            <a href="{{ route('admin.user.show', $bioLid->user->id) }}">
                                                {{ trim($bioLid->user->lastname.' '.$bioLid->user->name.' '.$bioLid->user->middlename) ?: $bioLid->user->email }}
                                            </a>
                                            <span class="text-muted">({{ $bioLid->user->email }})</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>{{ $bioLid->course->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия</td>
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
                                    <td>СНИЛС</td>
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
                                    <td>Образование</td>
                                    <td>{{ $bioLid->education ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Полное наименование организации</td>
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
                                    <td>Email</td>
                                    <td>{{ $bioLid->email }}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{{ $bioLid->phoneDisplay() }}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{ $bioLid->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{ $bioLid->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="alert"><b>История изменений</b></div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tr>
                                    <th>Дата</th>
                                    <th>Автор</th>
                                    <th>Что изменилось</th>
                                    <th>Изменение</th>
                                    <th></th>
                                </tr>
                                @forelse ($activites as $key => $activity)
                                    <tr>
                                        <td>{{ $activity->updated_at }}</td>
                                        <td>{{ $activity->user }}</td>
                                        <td>{{ $activity->description }}</td>
                                        <td>{{ $activity->status_old }} <i class="fas fa-arrow-right"></i> {{ $activity->status_new }}</td>
                                        <td>
                                            @if(!empty($activity->comment))
                                                <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="true">
                                                    <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="collapse{{ $key }}">
                                        <td colspan="5">
                                            <b>Комментарий: </b>{{ $activity->comment }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">Нет изменений</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert mb-2"><b>Документы и проверка</b></div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
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
                                            <form method="post" action="{{ route('admin.biolid.review-data', $bioLid) }}" class="biolid-review-form">
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
                                                <form method="post" action="{{ route('admin.biolid.review-document', [$bioLid, $document]) }}" class="biolid-review-form">
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
                </div>
            </div>

            <div class="row">
                <div class="col mb-4">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.biolid.index') }}">Назад</a>
                    <a class="btn btn-primary" href="{{ route('admin.biolid.edit', $bioLid->id) }}">Редактировать</a>

                    @if(!$bioLid->user)
                        <form method="post" action="{{ route('admin.biolid.create-user', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Создать или привязать учётную запись пользователя?');">
                            @csrf
                            <button class="btn btn-success" type="submit">Создать учётную запись</button>
                        </form>
                    @else
                        <a class="btn btn-outline-info" href="{{ route('admin.user.show', $bioLid->user->id) }}">Открыть пользователя</a>
                        <form method="post" action="{{ route('admin.biolid.send-credentials', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Будет сгенерирован новый пароль и отправлен на email пользователя. Продолжить?');">
                            @csrf
                            <button class="btn btn-warning" type="submit">Отправить письмо</button>
                        </form>
                    @endif

                    <form method="post" action="{{ route('admin.biolid.destroy', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Удалить заявку?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Удалить</button>
                    </form>
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
