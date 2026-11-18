@extends('admin.layouts.main')
@section('content')
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
                            <i class="ion ion-bag"></i>
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
                            <i class="ion ion-person-add"></i>
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
                            <i class="ion ion-pie-graph"></i>
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h3 class="card-title mb-0">Рейтинг курсов по заявкам</h3>
                            <div class="card-tools">
                                <form method="get" action="{{ route('admin.main.index') }}" class="form-inline">
                                    <label for="course-ranking-year" class="mr-2 mb-0">Год:</label>
                                    <select id="course-ranking-year" name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                                        @include('partials.year-options', [
                                            'yearOptions' => $years,
                                            'selectedYear' => $selectedYear,
                                        ])
                                    </select>
                                </form>
                            </div>
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
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('javascript')
    @if($courseRanking->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
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
            })();
        </script>
    @endif
@endsection
