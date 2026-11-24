@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заявки по БИО</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <form method="get" action="{{ route('admin.biolid.stats') }}" class="form-inline">
                        <label for="stats-study-group" class="mr-2 mb-0">Учебная группа:</label>
                        <select
                            id="stats-study-group"
                            name="study_group_id"
                            class="form-control form-control-sm"
                            style="min-width: 16rem;"
                            onchange="this.form.submit()"
                        >
                            <option value="">Все группы</option>
                            @foreach($studyGroups as $studyGroup)
                                <option
                                    value="{{ $studyGroup->id }}"
                                    {{ (string) $studyGroupId === (string) $studyGroup->id ? 'selected' : '' }}
                                >
                                    {{ $studyGroup->short_title ? $studyGroup->short_title.' — '.$studyGroup->title : $studyGroup->title }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $data['bioLidsCount'] }}</h3>
                            <p>Всего заявок по БИО</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dna"></i>
                        </div>
                        <a href="{{ route('admin.biolid.index') }}" class="small-box-footer">К списку заявок <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-success mb-4">
                        <div class="card-header px-4 py-3">
                            <h3 class="card-title mb-0">Высокая вероятность</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-baseline mb-3">
                                <span class="display-4 font-weight-bold text-success mb-0">{{ $data['funnel']['high'] }}</span>
                                <span class="text-muted ml-3">заявок могут зайти на обучение</span>
                            </div>
                            <ul class="list-unstyled mb-0">
                                @foreach($data['funnel']['highParts'] as $part)
                                    <li class="mb-2 d-flex justify-content-between align-items-center">
                                        <span class="text-muted">{{ $part['label'] }}</span>
                                        <span class="d-flex align-items-center ml-3">
                                            <span class="{{ $part['badgeClass'] }} mr-2">{{ $part['badge'] }}</span>
                                            <strong>{{ $part['count'] }}</strong>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card card-outline card-secondary mb-4">
                        <div class="card-header px-4 py-3">
                            <h3 class="card-title mb-0">Потенциально возможные</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-baseline mb-3">
                                <span class="display-4 font-weight-bold text-secondary mb-0">{{ $data['funnel']['potential'] }}</span>
                                <span class="text-muted ml-3">ещё не отправили документы</span>
                            </div>
                            <p class="text-muted mb-0">
                                Статус документов «Не отправлено», без заявок со статусом «Отказ».
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
