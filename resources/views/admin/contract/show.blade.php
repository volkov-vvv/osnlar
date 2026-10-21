@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Договор №{{ $contract->number }}</h1>
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
                                    <td>{{ $contract->id }}</td>
                                </tr>
                                <tr>
                                    <td>Номер договора</td>
                                    <td>{{ $contract->number }}</td>
                                </tr>
                                <tr>
                                    <td>Шаблон</td>
                                    <td>{{ $contract->template_title }}</td>
                                </tr>
                                <tr>
                                    <td>Проект</td>
                                    <td>{{ $contract->project->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Автор</td>
                                    <td>{{ $contract->author->full_name ?: $contract->author->name }}</td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>{{ $contract->course->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата подписания договора</td>
                                    <td>{{ $contract->signed_at ? $contract->signed_at->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата регистрации плательщика НПД</td>
                                    <td>{{ $contract->nap_registered_at ? $contract->nap_registered_at->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата окончания договора</td>
                                    <td>{{ $contract->ends_at ? $contract->ends_at->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{ $contract->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{ $contract->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex align-items-center pb-4">
                        <a class="btn btn-outline-primary mr-2" href="{{ route('admin.contract.edit', $contract->id) }}">Редактировать</a>
                        <a class="btn btn-outline-danger mr-2" href="{{ route('admin.contract.generate', $contract->id) }}">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.contract.index') }}">Назад</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
