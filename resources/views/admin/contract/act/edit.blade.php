@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование акта №{{ $act->number }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-lg-6 px-0">
                <div class="mb-3 text-muted">
                    Договор №{{ $act->contract->number }}<br>
                    Задание №{{ $act->assignment->number }}
                </div>

                <form action="{{ route('admin.contract_act.update', $act) }}" method="post">
                    @csrf
                    @method('PATCH')
                    @include('admin.contract.act._form', [
                        'act' => $act,
                        'assignment' => $act->assignment,
                        'fixedAssignment' => true,
                        'suggestedAmount' => \App\Models\ContractAct::suggestedAmountForAssignment($act->assignment),
                    ])

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.contract_act.show', $act) }}">Назад</a>
                </form>
            </div>
        </div>
    </section>
@endsection
