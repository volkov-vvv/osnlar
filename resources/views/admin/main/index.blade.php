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
                            <h3>93<sup style="font-size: 20px">%</sup></h3>

                            <p>Процент трудоустроенных</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">Подробнее <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <div class="row mb-3">
                <div class="col">
                    <p>Сколько сотрудников КЦ = {{$data['usersKcCount']}}</p>

                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="dashboard_table" class="table table-bordered table-striped hover">
                                        <thead>
                                        <tr>
                                            <th>ФИО</th>
                                            <th>Кол-во обработанных заявок</th>
                                            <th>% обработанных заявок</th>
                                            <th>Среднее время реакции</th>
                                            <th>Обучение</th>
                                            <th>Ждем на РР</th>
                                            <th>Недозвон</th>
                                            <th>Отказ</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{$user->name}}</td>
                                                <td>{{$user->getActiveLids()['count']}}</td>
                                                <td><span class="badge bg-danger">{{$user->getActiveLids()['persent']}}</span></td>
                                                <td>{{$user->averageTime()}}</td>
                                                <td>{{$user->getLids->where('status_id', 4)->count()}}</td>
                                                <td>{{$user->getLids->where('status_id', 6)->count()}}</td>
                                                <td>{{$user->getLids->where('status_id', 2)->count()}}</td>
                                                <td>{{$user->getLids->where('status_id', 9)->count()}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>



                                <div class="card-body">
                                    <table id="dashboard_table_ajax" class="table table-bordered table-striped hover">
                                        <thead>
                                        <tr>
                                            <th>ФИО</th>
                                            <th>Кол-во обработанных заявок</th>
                                            <th>% обработанных заявок</th>
                                            <th>Среднее время реакции</th>
                                            <th>Обучение</th>
                                            <th>Ждем на РР</th>
                                            <th>Недозвон</th>
                                            <th>Отказ</th>

                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>


                </div>
            </div>
                <!-- /.row -->


            </div><!-- /.container-fluid -->
    </section>


@endsection

@section('javascript')
    <script>
        $("#dashboard_table").DataTable({
            order: [[2, 'desc']],
            pageLength: 20,
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "colvis"],
            // "language": {
            //     url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/ru.json',
            // },
            "language": {
                info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                paginate: {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                search: "Поиск:",
                buttons: {
                    colvis: 'Выбрать колонки',
                    search: 'Поиск'
                },
            }
        }).buttons().container().appendTo('#dashboard_table_wrapper .col-md-6:eq(0)');


        $("#dashboard_table_ajax").DataTable({
            order: [[2, 'desc']],
            pageLength: 20,
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            //"buttons": ["excel", "pdf", "colvis"],
            // "language": {
            //     url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/ru.json',
            // },
            "language": {
                info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                paginate: {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                search: "Поиск:",
                buttons: {
                    colvis: 'Выбрать колонки',
                    search: 'Поиск'
                },
            },
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.dashboard.getDashboard')}}",
            columns: [
                { data: 'fio' },
                { data: 'count' },
                { data: 'percent' },
                { data: 'average_time' },
                { data: 'status_learning' },
                { data: 'status_pp' },
                { data: 'status_non_call' },
                { data: 'status_rejection' },
            ]
        }).buttons().container().appendTo('#dashboard_table_wrapper .col-md-6:eq(0)');
    </script>
@endsection
