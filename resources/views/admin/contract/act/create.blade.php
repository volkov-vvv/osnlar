@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Создание акта к договору №{{ $contract->number }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-lg-6 px-0">
                <div class="mb-3 text-muted">
                    Автор: {{ $contract->author->full_name ?: $contract->author->name }}<br>
                    Курс: {{ $contract->course->title ?? '—' }}
                </div>

                <form
                    action="{{ $fixedAssignment
                        ? route('admin.assignment.act.store', $assignment)
                        : route('admin.contract.act.store', $contract) }}"
                    method="post"
                >
                    @csrf
                    @include('admin.contract.act._form', [
                        'contract' => $contract,
                        'assignment' => $assignment,
                        'assignments' => $assignments,
                        'fixedAssignment' => $fixedAssignment,
                        'nextNumber' => $nextNumber,
                        'suggestedAmount' => $suggestedAmount,
                    ])

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a
                        class="btn btn-outline-secondary"
                        href="{{ $fixedAssignment
                            ? route('admin.assignment.show', $assignment)
                            : route('admin.contract.show', $contract) }}"
                    >Назад</a>
                </form>
            </div>
        </div>
    </section>
@endsection
