@extends('admin.layouts.main')
@section('content')
    <style>
        #region-ranking-card {
            min-height: 0;
        }
        #region-ranking-card .card-body {
            min-height: 0;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Главная</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{$data['lidsCount']}}</h3>

                            <p>Заявок</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">Подробнее <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$data['usersCount']}}</h3>

                            <p>Пользователей</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="#" class="small-box-footer">Подробнее <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{$data['coursesCount']}}</h3>

                            <p>Курсов</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="#" class="small-box-footer">Подробнее <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            @php
                                $kcCount = (int) $data['usersKcCount'];
                                $kcMod100 = abs($kcCount) % 100;
                                $kcMod10 = $kcMod100 % 10;
                                if ($kcMod100 > 10 && $kcMod100 < 20) {
                                    $kcWord = 'сотрудников';
                                } elseif ($kcMod10 > 1 && $kcMod10 < 5) {
                                    $kcWord = 'сотрудника';
                                } elseif ($kcMod10 === 1) {
                                    $kcWord = 'сотрудник';
                                } else {
                                    $kcWord = 'сотрудников';
                                }
                            @endphp
                            <h3>{{ $kcCount }} <small style="font-size: 40%; font-weight: 400;">{{ $kcWord }}</small></h3>

                            <p>Контакт-центр</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <a href="{{ route('admin.contactcenter.index') }}" class="small-box-footer">Подробнее <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <div class="row mb-3">
                <div class="col-12">
                    <form method="get" action="{{ route('admin.main.index') }}" class="form-inline">
                        <label for="dashboard-year" class="mr-2 mb-0">Год:</label>
                        <select id="dashboard-year" name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                            @include('partials.year-options', [
                                'yearOptions' => $years,
                                'selectedYear' => $selectedYear,
                            ])
                        </select>
                    </form>
                </div>
            </div>

            <div class="row align-items-stretch">
                <div class="col-lg-8 d-flex">
                    <div class="card w-100 mb-3" id="course-ranking-card">
                        <div class="card-header px-4 py-3">
                            <h3 class="card-title mb-0">Рейтинг курсов АМСЗ по заявкам</h3>
                        </div>
                        <div class="card-body">
                            @if($courseRanking->isEmpty())
                                <p class="text-center text-muted mb-0">Нет курсов за {{ $selectedYear }} год</p>
                            @else
                                <div style="position: relative; height: 360px;">
                                    <canvas id="courseRankingChart"></canvas>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-flex">
                    <div class="card w-100 mb-3 d-flex flex-column" id="region-ranking-card">
                        <div class="card-header px-4 py-3">
                            <h3 class="card-title mb-0">Топ‑7 регионов АМСЗ</h3>
                        </div>
                        <div class="card-body p-0 flex-grow-1" style="overflow: hidden;">
                            @if($regionRanking->isEmpty())
                                <p class="text-center text-muted p-3 mb-0">Нет заявок за {{ $selectedYear }} год</p>
                            @else
                                <div class="table-responsive h-100" style="overflow-y: auto;">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th style="width: 3rem;">#</th>
                                            <th>Регион</th>
                                            <th class="text-right" style="width: 5rem;">Заявок</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($regionRanking as $index => $region)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $region->title }}</td>
                                                <td class="text-right">{{ $region->lids_count }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('javascript')
    <script>
        (function () {
            function syncRegionCardHeight() {
                var courseCard = document.getElementById('course-ranking-card');
                var regionCard = document.getElementById('region-ranking-card');
                if (!courseCard || !regionCard || window.innerWidth < 992) {
                    if (regionCard) {
                        regionCard.style.maxHeight = '';
                    }
                    return;
                }
                regionCard.style.maxHeight = courseCard.offsetHeight + 'px';
            }

            window.addEventListener('load', syncRegionCardHeight);
            window.addEventListener('resize', syncRegionCardHeight);
        })();
    </script>
    @if($courseRanking->isNotEmpty())
        <script src="{{ asset('plugins/chart.js/chart.min.js') }}"></script>
        <script>
            (function () {
                var labels = @json($courseRanking->map(fn ($course) => $course->short_title ?: \Illuminate\Support\Str::limit($course->title, 28))->values());
                var titles = @json($courseRanking->pluck('title')->values());
                var values = @json($courseRanking->pluck('lids_count')->values());

                var canvas = document.getElementById('courseRankingChart');
                if (!canvas) {
                    return;
                }

                new Chart(canvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Заявок',
                            data: values,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            maxBarThickness: 48
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
                                callbacks: {
                                    title: function (items) {
                                        var index = items[0].dataIndex;
                                        return titles[index] || items[0].label;
                                    },
                                    label: function (context) {
                                        return 'Заявок: ' + context.raw;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'Заявок'
                                }
                            }
                        }
                    }
                });

                window.dispatchEvent(new Event('resize'));
            })();
        </script>
    @endif
@endsection
