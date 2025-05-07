@extends('admin.layouts.main')
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
<!--
    <div id="download" class="alert alert-info alert-dismissible fade" role="alert">
        Подготовка файла Excel...
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <a href="{{route('admin.lid.create')}}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>
            <!-- Small boxes (Stat box) -->

{{--            <div class="row mb-3">--}}
{{--                <div class="col">--}}
{{--                    <a href="{{route('admin.lid.create')}}" type="button" class="btn btn-primary"><i--}}
{{--                            class="fa-solid fa-plus"></i> Создать</a>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row">
                <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                    <tr class="active">
                                        <td>
                                            Компания:
                                            <select id="company" name="company" class="form-control form-control-sm">
                                                <option></option>
                                                @foreach($companies as $company)
                                                    <option value="{{$company->title}}">{{$company->title}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            Год:
                                            <select id="year" name="year" class="form-control form-control-sm">
                                                <option value="2025">2025</option>
                                                <option value="2024">2024</option>
                                            </select>
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr class="active">
                                        <td>
                                            Дата:<br>
                                            <input id="date" type="date" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            Ответсвенный:<br>
                                            <select id="responsible" name="responsible" class="form-control form-control-sm">
                                                <option></option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->name}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            Курс:<br>
                                            <select id="course" name="course" class="form-control form-control-sm">
                                                <option></option>
                                                @foreach($courses as $course)
                                                    <option value="{{$course->title}}">{{mb_substr($course->title, 0, 70)}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            Регион:<br>
                                            <select id="region" name="region" class="form-control form-control-sm">
                                                <option></option>
                                                @foreach($regions as $region)
                                                    <option value="{{$region->title}}">{{$region->title}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            Статус:<br>
                                            <select id="status" name="status" class="form-control form-control-sm">
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
                                        <th>Курс</th>
                                        <th>Регион</th>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Email</th>
                                        <th>Телефон</th>
                                        <th>Статус</th>
                                        <th>Реакция</th>
                                        <th>Дата создания</th>
                                        <th>Компания</th>
                                        <th>Год</th>
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
                        url: "{{route('admin.lid.getLidsExcel')}}",
                        method: 'get',
                        data: {
                            search: search,
                            columnSortName: columnSortName,
                            columnSortOrder: columnSortOrder,
                            filterResponsible: $('#responsible').val(),
                            filterCourse: $('#course').val(),
                            filterRegion: $('#region').val(),
                            filterStatus: $('#status').val(),
                            filterYear: $('#year').val(),
                        },
                        xhrFields:{
                            responseType: 'blob'
                        },
                        success: function(data)
                        {
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(data);
                            link.download = `Report.xlsx`;
                            link.click();
                            $('#download').alert('close');
                        },
                    });
                }
            }, "colvis"],
            order: [[0, 'desc']],
            'columnDefs': [ {
                'targets': [9,12], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            },
                { targets: [12], visible: false }
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
            ajax: "{{route('admin.lid.getLids')}}?commerce={{$commerce}}",
            columns: [
                { data: 'id' },
                { data: 'responsible' },
                { data: 'course' },
                { data: 'region' },
                { data: 'lastname' },
                { data: 'firstname' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'status' },
                { data: 'interval' },
                { data: 'created_at' },
                { data: 'company' },
                { data: 'year' },
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
                .column(2)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#region').on('change', function (e){

            table
                .column(3)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#status').on('change', function (e){

            table
                .column(8)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#date').on('change', function (e){

            table
                .column(10)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#company').on('change', function (e){

            table
                .column(11)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#year').on('change', function (e){

            table
                .column(12)
                .search(this.value, {exact: true})
                .draw();
        })

    </script>
@endsection
