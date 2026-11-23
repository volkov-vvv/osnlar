@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Задание №{{ $assignment->number }} к договору №{{ $assignment->contract->number }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $assignment->id }}</td>
                                </tr>
                                <tr>
                                    <td>Номер задания</td>
                                    <td>{{ $assignment->number }}</td>
                                </tr>
                                <tr>
                                    <td>Договор</td>
                                    <td>
                                        <a href="{{ route('admin.contract.show', $assignment->contract) }}">
                                            №{{ $assignment->contract->number }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Автор</td>
                                    <td>{{ $assignment->contract->author->full_name ?: $assignment->contract->author->name }}</td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>{{ $assignment->contract->course->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Учебная группа</td>
                                    <td>{{ $assignment->studyGroupLabel() }}</td>
                                </tr>
                                <tr>
                                    <td>Виды работ</td>
                                    <td style="white-space: pre-wrap;">{{ $assignment->work_types }}</td>
                                </tr>
                                <tr>
                                    <td>Стоимость часа</td>
                                    <td>{{ $assignment->hourlyRateFormatted() }} руб.</td>
                                </tr>
                                <tr>
                                    <td>Дата задания</td>
                                    <td>{{ $assignment->issued_at ? $assignment->issued_at->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Срок оказания услуг</td>
                                    <td>
                                        {{ $assignment->starts_at ? $assignment->starts_at->format('d.m.Y') : '—' }}
                                        –
                                        {{ $assignment->ends_at ? $assignment->ends_at->format('d.m.Y') : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{ $assignment->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{ $assignment->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4 flex-wrap">
                        <a class="btn btn-outline-primary mr-2 mb-2" href="{{ route('admin.assignment.edit', $assignment) }}">Редактировать</a>
                        <a class="btn btn-outline-danger mr-2 mb-2" href="{{ route('admin.assignment.generate', $assignment) }}">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                        <form method="post" action="{{ route('admin.assignment.destroy', $assignment) }}" class="d-inline mb-2 mr-2" onsubmit="return confirm('Удалить задание?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary">Удалить</button>
                        </form>
                        <a class="btn btn-outline-secondary mb-2" href="{{ route('admin.contract.show', $assignment->contract) }}">К договору</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
