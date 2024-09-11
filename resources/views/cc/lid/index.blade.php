@extends('cc.layouts.main')
@section('content')
    <div id="alert-area"></div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заявки</h1>
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
                <div class="col">
                        <div class="card">

                            <div class="card-body">
                                <table class="table table-striped mb-2">
                                    <tbody>
                                    <tr>
                                        <td>
                                            Дата:
                                            <input id="date" type="date">
                                        </td>
                                        <td>
                                            Ответсвенный:
                                            <select id="responsible" name="responsible">
                                                <option></option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->name}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            Курс:
                                            <select id="course" name="course">
                                                <option></option>
                                                @foreach($courses as $course)
                                                    <option value="{{$course->title}}">{{mb_substr($course->title, 0, 70)}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            Регион:
                                            <select id="region" name="region">
                                                <option></option>
                                                @foreach($regions as $region)
                                                    <option value="{{$region->title}}">{{$region->title}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            Статус:
                                            <select id="status" name="status">
                                                <option></option>
                                                @foreach($statuses as $status)
                                                    <option value="{{$status->title}}">{{$status->title}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table id="example2" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Ответственный</th>
                                        <th>Агент</th>
                                        <th>Курс</th>
                                        <th>Регион</th>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Email</th>
                                        <th>Телефон</th>
                                        <th>Статус</th>
                                        <th>Реакция</th>
                                        <th>Дата создания</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-->
                </div>
            </div>

            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
@endsection


@section('javascript')
    <script>
        var table = new DataTable('#example2', {
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "buttons": [{ extend: 'excel',
                text: 'Excel',
                action: function (e, dt, node, config)
                {
                    var rowData = dt.rows({ filter: 'applied' }).data();
                    var rowKeys = Object.keys(rowData[0]);
                    var OrderData = dt.order();
                    var columnSortName = rowKeys[OrderData[0][0]];
                    var columnSortOrder = OrderData[0][1];
                    var search = dt.search();
                    $('#alert-area').html('<div id="download" class="alert alert-info alert-dismissible fade" role="alert">Подготовка файла Excel...<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
                    $('#download').addClass('show');
                    $.ajax({
                        url: "{{route('cc.lid.getLidsExcel')}}",
                        method: 'get',
                        data: {
                            search: search,
                            columnSortName: columnSortName,
                            columnSortOrder: columnSortOrder,
                            filterResponsible: $('#responsible').val(),
                            filterCourse: $('#course').val(),
                            filterRegion: $('#region').val(),
                            filterStatus: $('#status').val(),
                        },
                        xhrFields:{
                            responseType: 'blob'
                        },
                        success: function(data)
                        {
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(data);
                            link.download = `Lids_report.xlsx`;
                            link.click();
                            $('#download').alert('close');
                        },
                    });
                }
            }, "colvis"],
            order: [[0, 'desc']],
            'columnDefs': [ {
                'targets': [10,12], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            },
                { targets: [2], visible: false }
            ],
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
            ajax: "{{route('cc.lid.getLids')}}",
            columns: [
                { data: 'id' },
                { data: 'responsible' },
                { data: 'agent' },
                { data: 'course' },
                { data: 'region' },
                { data: 'lastname' },
                { data: 'firstname' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'status' },
                { data: 'interval' },
                { data: 'created_at' },
                { data: 'actions' },
            ]
        });

        table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');



        $('#responsible').on('change', function (e){

            table
                .column(1)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#course').on('change', function (e){

            table
                .column(3)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#region').on('change', function (e){

            table
                .column(4)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#status').on('change', function (e){

            table
                .column(9)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#date').on('change', function (e){

            table
                .column(11)
                .search(this.value, {exact: true})
                .draw();
        })

    </script>
@endsection
