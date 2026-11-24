@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование отчёта №{{ $report->displayNumber() }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-lg-6 px-0">
                <div class="mb-3 text-muted">
                    Договор №{{ $report->contract->number }}<br>
                    Задание №{{ $report->assignment->number }}
                </div>

                <form action="{{ route('admin.contract_report.update', $report) }}" method="post">
                    @csrf
                    @method('PATCH')
                    @include('admin.contract.report._form', [
                        'report' => $report,
                        'assignment' => $report->assignment,
                        'fixedAssignment' => true,
                        'kind' => $report->kind,
                    ])

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.contract_report.show', $report) }}">Назад</a>
                </form>
            </div>
        </div>
    </section>
@endsection
