@extends('admin.layouts.main')
@section('content')
    <section class="content contract-show-page">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="contract-show-header">
                <div class="contract-show-header__title-row">
                    <h1 class="contract-show-header__title">Задание №{{ $assignment->number }}</h1>
                    @include('admin.contract._status_badge', ['status' => $assignment->status])
                </div>

                <div class="contract-show-header__subtitle">
                    к договору
                    <a href="{{ route('admin.contract.show', $assignment->contract) }}">№{{ $assignment->contract->number }}</a>
                    ·
                    {{ $assignment->contract->author->full_name ?: $assignment->contract->author->name }}
                    ·
                    @include('admin.contract._course_title_hint', ['course' => $assignment->contract->course])
                </div>

                <dl class="contract-meta-grid">
                    <div class="contract-meta-grid__item">
                        <dt>Учебная группа</dt>
                        <dd>{{ $assignment->studyGroupLabel() }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Вид оплаты</dt>
                        <dd>{{ $assignment->paymentTypeLabel() }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Сумма</dt>
                        <dd>{{ $assignment->paymentAmountShortLabel() }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Дата задания</dt>
                        <dd>{{ $assignment->issued_at ? $assignment->issued_at->format('d.m.Y') : '—' }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Срок услуг</dt>
                        <dd>
                            {{ $assignment->starts_at ? $assignment->starts_at->format('d.m.Y') : '—' }}
                            –
                            {{ $assignment->ends_at ? $assignment->ends_at->format('d.m.Y') : '—' }}
                        </dd>
                    </div>
                </dl>

                <div class="mb-3">
                    <div class="contract-meta-grid__item">
                        <dt>Виды работ</dt>
                        <dd style="white-space: pre-wrap;">{{ $assignment->work_types ?: '—' }}</dd>
                    </div>
                </div>

                <div class="contract-actions">
                    <div class="contract-actions__group">
                        <a class="btn btn-primary" href="{{ route('admin.assignment.edit', $assignment) }}">Редактировать</a>
                        @include('admin.contract._scanned_pdf', [
                            'model' => $assignment,
                            'uploadUrl' => route('admin.assignment.uploadScan', $assignment),
                            'downloadUrl' => route('admin.assignment.downloadScan', $assignment),
                            'destroyUrl' => route('admin.assignment.destroyScan', $assignment),
                        ])
                        <a class="btn btn-outline-danger" href="{{ route('admin.assignment.generate', $assignment) }}">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                    </div>
                    <span class="contract-actions__sep" aria-hidden="true"></span>
                    <div class="contract-actions__group">
                        <form method="post" action="{{ route('admin.assignment.destroy', $assignment) }}" class="d-inline" onsubmit="return confirm('Удалить задание?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary">Удалить</button>
                        </form>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.contract.show', $assignment->contract) }}">К договору</a>
                    </div>
                </div>

                <div class="contract-show-header__timestamps">
                    Создан {{ $assignment->created_at }} · обновлён {{ $assignment->updated_at }}
                </div>
            </div>

            <div class="contract-assignment-block">
                <div class="contract-assignment-block__head">
                    <div>
                        <h4 class="contract-assignment-block__title mb-0">Отчёт и акты</h4>
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
        </div>
    </section>
@endsection

@section('javascript')
    @include('admin.contract._course_title_hint_script')
@endsection
