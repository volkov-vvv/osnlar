@extends('admin.layouts.main')
@section('content')
    <section class="content contract-show-page">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="contract-show-header">
                <div class="contract-show-header__title-row">
                    <h1 class="contract-show-header__title">Отчёт №{{ $report->displayNumber() }}</h1>
                    @include('admin.contract._status_badge', ['status' => $report->status])
                </div>

                <div class="contract-show-header__subtitle">
                    к заданию
                    <a href="{{ route('admin.assignment.show', $report->assignment) }}">№{{ $report->assignment->number }}</a>
                    ·
                    договор
                    <a href="{{ route('admin.contract.show', $report->contract) }}">№{{ $report->contract->number }}</a>
                    ·
                    {{ $report->contract->author->full_name ?: $report->contract->author->name }}
                    @if($report->isPerStudent())
                        · за успешно обученных
                    @endif
                </div>

                <dl class="contract-meta-grid">
                    <div class="contract-meta-grid__item">
                        <dt>Курс</dt>
                        <dd>@include('admin.contract._course_title_hint', ['course' => $report->contract->course])</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Дата отчёта</dt>
                        <dd>{{ $report->issued_at ? $report->issued_at->format('d.m.Y') : '—' }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Срок услуг</dt>
                        <dd>
                            {{ $report->starts_at ? $report->starts_at->format('d.m.Y') : '—' }}
                            –
                            {{ $report->ends_at ? $report->ends_at->format('d.m.Y') : '—' }}
                        </dd>
                    </div>
                    @if($report->isPerStudent())
                        <div class="contract-meta-grid__item">
                            <dt>Слушатели</dt>
                            <dd>
                                {{ $report->successfulStudentsCount() }} чел.
                                @if($report->successfulStudentsList())
                                    <div class="mt-1 text-muted" style="font-weight: normal;">
                                        {{ implode(', ', $report->successfulStudentsList()) }}
                                    </div>
                                @endif
                            </dd>
                        </div>
                    @else
                        <div class="contract-meta-grid__item">
                            <dt>Акад. часы</dt>
                            <dd>{{ $report->lessonHoursFormatted() }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="contract-actions">
                    <div class="contract-actions__group">
                        <a class="btn btn-primary" href="{{ route('admin.contract_report.edit', $report) }}">Редактировать</a>
                        @include('admin.contract._scanned_pdf', [
                            'model' => $report,
                            'uploadUrl' => route('admin.contract_report.uploadScan', $report),
                            'downloadUrl' => route('admin.contract_report.downloadScan', $report),
                            'destroyUrl' => route('admin.contract_report.destroyScan', $report),
                        ])
                        <a class="btn btn-outline-danger" href="{{ route('admin.contract_report.generate', $report) }}">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                        <a class="btn btn-outline-primary" href="{{ route('admin.contract_report.generateDocx', $report) }}">
                            <i class="fas fa-file-word mr-1"></i> DOCX
                        </a>
                    </div>
                    <span class="contract-actions__sep" aria-hidden="true"></span>
                    <div class="contract-actions__group">
                        <form method="post" action="{{ route('admin.contract_report.destroy', $report) }}" class="d-inline" onsubmit="return confirm('Удалить отчёт?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary">Удалить</button>
                        </form>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.assignment.show', $report->assignment) }}">К заданию</a>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.contract.show', $report->contract) }}">К договору</a>
                    </div>
                </div>

                <div class="contract-show-header__timestamps">
                    Создан {{ $report->created_at }} · обновлён {{ $report->updated_at }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    @include('admin.contract._course_title_hint_script')
@endsection
