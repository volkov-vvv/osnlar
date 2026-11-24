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
                        <dt>Окончание</dt>
                        <dd>{{ $contract->ends_at ? $contract->ends_at->format('d.m.Y') : '—' }}</dd>
                    </div>
                </dl>

                <div class="contract-actions">
                    <div class="contract-actions__group">
                        <a class="btn btn-primary" href="{{ route('admin.contract.edit', $contract->id) }}">Редактировать</a>
                        @include('admin.contract._scanned_pdf', [
                            'model' => $contract,
                            'uploadUrl' => route('admin.contract.uploadScan', $contract),
                            'downloadUrl' => route('admin.contract.downloadScan', $contract),
                            'destroyUrl' => route('admin.contract.destroyScan', $contract),
                        ])
                        <a class="btn btn-outline-danger" href="{{ route('admin.contract.generate', $contract->id) }}">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                        <a class="btn btn-outline-primary" href="{{ route('admin.contract.generateDocx', $contract) }}">
                            <i class="fas fa-file-word mr-1"></i> DOCX
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
                        <form method="post" action="{{ route('admin.contract.destroy', $contract) }}" class="d-inline" onsubmit="return confirm('Удалить договор?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary">Удалить</button>
                        </form>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.contract.index') }}">Назад</a>
                    </div>
                </div>

                <div class="contract-show-header__timestamps">
                    Создан {{ $contract->created_at }} · обновлён {{ $contract->updated_at }}
                </div>
            </div>

            <div class="card card-outline card-secondary mb-3">
                <div class="card-header p-3">
                    <h3 class="card-title mb-0">Задания ({{ $contract->assignments->count() }})</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
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
                                        {{ $assignment->paymentTypeLabel() }}
                                        ·
                                        {{ $assignment->paymentAmountShortLabel() }}
                                    </div>
                                </div>
                                <div class="contract-assignment-block__actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.assignment.show', $assignment) }}" title="Просмотр" aria-label="Просмотр">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.assignment.edit', $assignment) }}" title="Редактировать" aria-label="Редактировать">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.assignment.generate', $assignment) }}">PDF</a>
                                </div>
                            </div>

                            <div class="contract-assignment-block__body">
                                @if($assignment->hasBasePayment())
                                    <div class="contract-nested-row">
                                        <span class="contract-nested-row__label">Отчёт</span>
                                        <div class="contract-nested-row__main">
                                            @if($assignment->report)
                                                <a href="{{ route('admin.contract_report.show', $assignment->report) }}">
                                                    №{{ $assignment->report->displayNumber() }}
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
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.contract_report.show', $assignment->report) }}" title="Просмотр" aria-label="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_report.edit', $assignment->report) }}" title="Редактировать" aria-label="Редактировать">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.contract_report.generate', $assignment->report) }}">PDF</a>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_report.generateDocx', $assignment->report) }}">DOCX</a>
                                            </div>
                                        @endif
                                    </div>

                                    @forelse($assignment->acts as $act)
                                        <div class="contract-nested-row">
                                            <span class="contract-nested-row__label">Акт</span>
                                            <div class="contract-nested-row__main">
                                                <a href="{{ route('admin.contract_act.show', $act) }}">№{{ $act->displayNumber() }}</a>
                                                @include('admin.contract._status_badge', ['status' => $act->status])
                                                <span class="contract-nested-row__meta">
                                                    {{ $act->issued_at ? $act->issued_at->format('d.m.Y') : '—' }}
                                                    ·
                                                    {{ $act->amountFormatted() }} руб.
                                                </span>
                                            </div>
                                            <div class="contract-nested-row__actions">
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.contract_act.show', $act) }}" title="Просмотр" aria-label="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_act.edit', $act) }}" title="Редактировать" aria-label="Редактировать">
                                                    <i class="fas fa-pen"></i>
                                                </a>
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
                                @endif

                                @if($assignment->isPaymentPerStudent())
                                    <div class="contract-nested-row">
                                        <span class="contract-nested-row__label">Отчёт {{ $assignment->number }}.2</span>
                                        <div class="contract-nested-row__main">
                                            @if($assignment->perStudentReport)
                                                <a href="{{ route('admin.contract_report.show', $assignment->perStudentReport) }}">
                                                    №{{ $assignment->perStudentReport->displayNumber() }}
                                                </a>
                                                @include('admin.contract._status_badge', ['status' => $assignment->perStudentReport->status])
                                                <span class="contract-nested-row__meta">
                                                    {{ $assignment->perStudentReport->successfulStudentsCount() }} слушат.
                                                </span>
                                            @else
                                                <span class="text-muted">Нет отчёта N.2</span>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.assignment.report.create', ['assignment' => $assignment, 'kind' => 'per_student']) }}">
                                                    Создать отчёт {{ $assignment->number }}.2
                                                </a>
                                            @endif
                                        </div>
                                        @if($assignment->perStudentReport)
                                            <div class="contract-nested-row__actions">
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.contract_report.show', $assignment->perStudentReport) }}" title="Просмотр" aria-label="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_report.edit', $assignment->perStudentReport) }}" title="Редактировать" aria-label="Редактировать">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.contract_report.generate', $assignment->perStudentReport) }}">PDF</a>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_report.generateDocx', $assignment->perStudentReport) }}">DOCX</a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="contract-nested-row">
                                        <span class="contract-nested-row__label">Акт {{ $assignment->number }}.2</span>
                                        <div class="contract-nested-row__main">
                                            @if($assignment->perStudentAct)
                                                <a href="{{ route('admin.contract_act.show', $assignment->perStudentAct) }}">
                                                    №{{ $assignment->perStudentAct->displayNumber() }}
                                                </a>
                                                @include('admin.contract._status_badge', ['status' => $assignment->perStudentAct->status])
                                                <span class="contract-nested-row__meta">
                                                    {{ $assignment->perStudentAct->issued_at ? $assignment->perStudentAct->issued_at->format('d.m.Y') : '—' }}
                                                    ·
                                                    {{ $assignment->perStudentAct->amountFormatted() }} руб.
                                                </span>
                                            @elseif($assignment->perStudentReport)
                                                <span class="text-muted">Нет акта N.2</span>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.assignment.act.create', ['assignment' => $assignment, 'kind' => 'per_student']) }}">
                                                    Создать акт {{ $assignment->number }}.2
                                                </a>
                                            @else
                                                <span class="text-muted">Сначала создайте отчёт N.2</span>
                                            @endif
                                        </div>
                                        @if($assignment->perStudentAct)
                                            <div class="contract-nested-row__actions">
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.contract_act.show', $assignment->perStudentAct) }}" title="Просмотр" aria-label="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_act.edit', $assignment->perStudentAct) }}" title="Редактировать" aria-label="Редактировать">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.contract_act.generate', $assignment->perStudentAct) }}">PDF</a>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contract_act.generateDocx', $assignment->perStudentAct) }}">DOCX</a>
                                            </div>
                                        @endif
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
            </div>

            @include('admin.contract._history', ['activities' => $activities])
        </div>
    </section>
@endsection

@section('javascript')
    @include('admin.contract._course_title_hint_script')
@endsection
