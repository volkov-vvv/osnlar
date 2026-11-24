@extends('admin.layouts.main')
@section('content')
    <section class="content contract-show-page">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="contract-show-header">
                <div class="contract-show-header__title-row">
                    <h1 class="contract-show-header__title">Акт №{{ $act->displayNumber() }}</h1>
                    @include('admin.contract._status_badge', ['status' => $act->status])
                </div>

                <div class="contract-show-header__subtitle">
                    к заданию
                    <a href="{{ route('admin.assignment.show', $act->assignment) }}">№{{ $act->assignment->number }}</a>
                    ·
                    договор
                    <a href="{{ route('admin.contract.show', $act->contract) }}">№{{ $act->contract->number }}</a>
                    ·
                    {{ $act->contract->author->full_name ?: $act->contract->author->name }}
                    @if($act->isPerStudent())
                        · за успешно обученных
                    @endif
                </div>

                <dl class="contract-meta-grid">
                    <div class="contract-meta-grid__item">
                        <dt>Курс</dt>
                        <dd>@include('admin.contract._course_title_hint', ['course' => $act->contract->course])</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Дата акта</dt>
                        <dd>{{ $act->issued_at ? $act->issued_at->format('d.m.Y') : '—' }}</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Стоимость услуг</dt>
                        <dd>{{ $act->amountFormatted() }} руб.</dd>
                    </div>
                    <div class="contract-meta-grid__item">
                        <dt>Оплата по заданию</dt>
                        <dd>{{ $act->assignment->paymentAmountShortLabel() }}</dd>
                    </div>
                </dl>

                <div class="contract-actions">
                    <div class="contract-actions__group">
                        <a class="btn btn-primary" href="{{ route('admin.contract_act.edit', $act) }}">Редактировать</a>
                        @include('admin.contract._scanned_pdf', [
                            'model' => $act,
                            'uploadUrl' => route('admin.contract_act.uploadScan', $act),
                            'downloadUrl' => route('admin.contract_act.downloadScan', $act),
                            'destroyUrl' => route('admin.contract_act.destroyScan', $act),
                        ])
                        <a class="btn btn-outline-danger" href="{{ route('admin.contract_act.generate', $act) }}">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                        <a class="btn btn-outline-primary" href="{{ route('admin.contract_act.generateDocx', $act) }}">
                            <i class="fas fa-file-word mr-1"></i> DOCX
                        </a>
                    </div>
                    <span class="contract-actions__sep" aria-hidden="true"></span>
                    <div class="contract-actions__group">
                        <form method="post" action="{{ route('admin.contract_act.destroy', $act) }}" class="d-inline" onsubmit="return confirm('Удалить акт?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary">Удалить</button>
                        </form>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.assignment.show', $act->assignment) }}">К заданию</a>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.contract.show', $act->contract) }}">К договору</a>
                    </div>
                </div>

                <div class="contract-show-header__timestamps">
                    Создан {{ $act->created_at }} · обновлён {{ $act->updated_at }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    @include('admin.contract._course_title_hint_script')
@endsection
