@extends('admin.layouts.main')
@section('content')
    <section class="content contract-show-page">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="contract-show-header">
                <div class="contract-show-header__title-row">
                    <h1 class="contract-show-header__title">Договор №{{ $contract->number }}</h1>
                    @include('admin.contract._status_badge', ['status' => $contract->status])
                </div>

                <div class="contract-show-header__subtitle">
                    {{ $contract->author->full_name ?: $contract->author->name }}
                    ·
                    @include('admin.contract._course_title_hint', ['course' => $contract->course])
                    ·
                    {{ $contract->project->title ?? '—' }}
                </div>

                <dl class="contract-meta-grid">
                    <div class="contract-meta-grid__item">
                        <dt>Шаблон</dt>
                        <dd>{{ $contract->template_title }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Подписание</dt>
                        <dd>{{ $contract->signed_at ? $contract->signed_at->format('d.m.Y') : '—' }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>НПД</dt>
                        <dd>{{ $contract->nap_registered_at ? $contract->nap_registered_at->format('d.m.Y') : '—' }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Окончание</dt>
                        <dd>{{ $contract->ends_at ? $contract->ends_at->format('d.m.Y') : '—' }}</dd>
                    </div>
                </dl>

                <div class="contract-actions">
                    <div class="contract-actions__group">
                        <a class="btn btn-primary" href="{{ route('admin.contract.edit', $contract->id) }}">Редактировать</a>
                        <a class="btn btn-outline-danger" href="{{ route('admin.contract.generate', $contract->id) }}">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                    </div>
                    <span class="contract-actions__sep" aria-hidden="true"></span>
                    <div class="contract-actions__group">
                        <a class="btn btn-outline-primary" href="{{ route('admin.contract.assignment.create', $contract) }}">
                            Создать задание
                        </a>
                    </div>
                    <span class="contract-actions__sep" aria-hidden="true"></span>
                    <div class="contract-actions__group">
                        <a class="btn btn-outline-secondary" href="{{ route('admin.contract.index') }}">Назад</a>
                    </div>
                </div>

                <div class="contract-show-header__timestamps">
                    Создан {{ $contract->created_at }} · обновлён {{ $contract->updated_at }}
                </div>
            </div>

            <h3 class="h5 mb-3">Задания</h3>

            @forelse($contract->assignments as $assignment)
                <div class="contract-assignment-block">
                    <div class="contract-assignment-block__head">
                        <div>
                            <h4 class="contract-assignment-block__title">
                                <a href="{{ route('admin.assignment.show', $assignment) }}">Задание №{{ $assignment->number }}</a>
                                <span class="ml-2">@include('admin.contract._status_badge', ['status' => $assignment->status])</span>
                            </h4>
                            <div class="contract-assignment-block__meta">
                                {{ $assignment->studyGroup->title ?? '—' }}
                                ·
                                {{ $assignment->starts_at ? $assignment->starts_at->format('d.m.Y') : '—' }}
                                –
                                {{ $assignment->ends_at ? $assignment->ends_at->format('d.m.Y') : '—' }}
                                ·
                                {{ $assignment->hourlyRateFormatted() }} руб./час
                            </div>
                        </div>
                        <div class="contract-assignment-block__actions">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.assignment.show', $assignment) }}">Просмотр</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.assignment.edit', $assignment) }}">Редактировать</a>
                            <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.assignment.generate', $assignment) }}">PDF</a>
                        </div>
                    </div>

                    <div class="contract-assignment-block__body">
                        <div class="contract-nested-row">
                            <span class="contract-nested-row__label">Отчёт</span>
                            <div class="contract-nested-row__main">
                                @if($assignment->report)
                                    <a href="{{ route('admin.contract_report.show', $assignment->report) }}">
                                        №{{ $assignment->report->number }}
                                    </a>
                                    @include('admin.contract._status_badge', ['status' => $assignment->report->status])
                                    <span class="contract-nested-row__meta">
                                        {{ $assignment->report->lessonHoursFormatted() }} акад. ч.
                                        @if($assignment->report->starts_at || $assignment->report->ends_at)
                                            ·
                                            {{ $assignment->report->starts_at ? $assignment->report->starts_at->format('d.m.Y') : '—' }}
                                            –
                                            {{ $assignment->report->ends_at ? $assignment->report->ends_at->format('d.m.Y') : '—' }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted">Нет отчёта</span>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.assignment.report.create', $assignment) }}">
                                        Создать отчёт
                                    </a>
                                @endif
                            </div>
                            @if($assignment->report)
                                <div class="contract-nested-row__actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.contract_report.show', $assignment->report) }}">Просмотр</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_report.edit', $assignment->report) }}">Редактировать</a>
                                    <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.contract_report.generate', $assignment->report) }}">PDF</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_report.generateDocx', $assignment->report) }}">DOCX</a>
                                </div>
                            @endif
                        </div>

                        @forelse($assignment->acts as $act)
                            <div class="contract-nested-row">
                                <span class="contract-nested-row__label">Акт</span>
                                <div class="contract-nested-row__main">
                                    <a href="{{ route('admin.contract_act.show', $act) }}">№{{ $act->number }}</a>
                                    @include('admin.contract._status_badge', ['status' => $act->status])
                                    <span class="contract-nested-row__meta">
                                        {{ $act->issued_at ? $act->issued_at->format('d.m.Y') : '—' }}
                                        ·
                                        {{ $act->amountFormatted() }} руб.
                                    </span>
                                </div>
                                <div class="contract-nested-row__actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.contract_act.show', $act) }}">Просмотр</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_act.edit', $act) }}">Редактировать</a>
                                    <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.contract_act.generate', $act) }}">PDF</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_act.generateDocx', $act) }}">DOCX</a>
                                </div>
                            </div>
                        @empty
                            <div class="contract-nested-row">
                                <span class="contract-nested-row__label">Акт</span>
                                <div class="contract-nested-row__main">
                                    <span class="text-muted">Нет актов</span>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.assignment.act.create', $assignment) }}">
                                        Создать акт
                                    </a>
                                </div>
                            </div>
                        @endforelse

                        @if($assignment->acts->isNotEmpty())
                            <div class="contract-nested-row">
                                <span class="contract-nested-row__label"></span>
                                <div class="contract-nested-row__main">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.assignment.act.create', $assignment) }}">
                                        Создать акт
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="contract-empty-state">
                    <p class="mb-3">Заданий пока нет</p>
                    <a class="btn btn-primary" href="{{ route('admin.contract.assignment.create', $contract) }}">
                        Создать задание
                    </a>
                </div>
            @endforelse
        </div>
    </section>
@endsection

@section('javascript')
    @include('admin.contract._course_title_hint_script')
@endsection
