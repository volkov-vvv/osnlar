@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование задания №{{ $assignment->number }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-lg-6 px-0">
                <div class="mb-3 text-muted">
                    Договор №{{ $assignment->contract->number }}<br>
                    Автор: {{ $assignment->contract->author->full_name ?: $assignment->contract->author->name }}<br>
                    Курс: {{ $assignment->contract->course->title ?? '—' }}
                </div>

                <form action="{{ route('admin.assignment.update', $assignment) }}" method="post">
                    @csrf
                    @method('PATCH')
                    @include('admin.contract.assignment._form', ['assignment' => $assignment, 'studyGroups' => $studyGroups])

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.assignment.show', $assignment) }}">Назад</a>
                </form>
            </div>
        </div>
    </section>
@endsection
