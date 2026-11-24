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
                                <span class="text-muted ml-3">заявок могут выйти на обучение</span>
                            </div>
                            <ul class="list-unstyled mb-0">
                                @foreach($data['funnel']['highParts'] as $part)
                                    <li class="mb-2 d-flex justify-content-between">
                                        <span class="text-muted">{{ $part['label'] }}</span>
                                        <strong>{{ $part['count'] }}</strong>
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
                <div class="col-md-6">
                    <div class="card card-outline card-secondary mb-4">
                        <div class="card-header px-4 py-3">
                            <h3 class="card-title">Документы</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row align-items-center px-2 py-3">
                                <div class="position-relative" style="width: 260px; height: 260px;">
                                    <canvas id="docsStatusChart"></canvas>
                                </div>
                                <ul class="list-unstyled mb-0 ml-md-5 mt-4 mt-md-0">
                                    @foreach($data['docsStats'] as $docsStat)
                                        <li class="mb-3 d-flex align-items-center">
                                            <span
                                                class="d-inline-block mr-2 rounded-circle"
                                                style="width: 12px; height: 12px; background-color: {{ $docsStat['color'] }};"
                                            ></span>
                                            <span>{{ $docsStat['label'] }}: <strong>{{ $docsStat['count'] }}</strong></span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mt-4 pt-2">
                                <a href="{{ route('admin.biolid.index') }}">Перейти к заявкам <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/chart.js/chart.min.js') }}"></script>
    <script>
        (function () {
            var labels = @json(collect($data['docsStats'])->pluck('label')->values());
            var values = @json(collect($data['docsStats'])->pluck('count')->values());
            var colors = @json(collect($data['docsStats'])->pluck('color')->values());
            var total = values.reduce(function (sum, value) { return sum + Number(value); }, 0);

            var canvas = document.getElementById('docsStatusChart');
            if (!canvas) {
                return;
            }

            new Chart(canvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: total > 0 ? values : [1],
                        backgroundColor: total > 0 ? colors : ['#dee2e6'],
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: total > 0,
                            callbacks: {
                                label: function (context) {
                                    var label = context.label || '';
                                    var value = context.raw || 0;
                                    return label + ': ' + value;
                                }
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endsection
