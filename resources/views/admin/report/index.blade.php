@extends('admin.layouts.main')
@section('content')
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

            <div class="row">
                <div class="col">
                        <div class="card">

                            <div class="card-body">

                                <table class="table table-striped mb-2">
                                    <tbody>
                                    <tr>
                                        <td>
                                            Агент:
                                            <select id="agent" name="agent">
                                                <option></option>
                                                @foreach($agents as $agent)
                                                    <option value="{{$agent->title}}">{{$agent->title}}</option>
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
                                        <td>
                                            utm_source:
                                            <select id="utm_source" name="utm_source">
                                                <option></option>
                                                @foreach($sources as $source)
                                                    <option value="{{$source->utm_source}}">{{$source->utm_source}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table id="report" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Агент</th>
                                        <th>Регион</th>
                                        <th>Курс</th>
                                        <th>Поток</th>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Категория</th>
                                        <th>Статус</th>
                                        <th>Дата создания</th>
                                        <th>utm_source</th>
                                        <th>utm_medium</th>
                                        <th>utm_campaign</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
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


        var table = new DataTable('#report', {
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
                    $.ajax({
                        url: "{{route('admin.report.getReportExcel')}}",
                        method: 'get',
                        data: {
                            search: search,
                            columnSortName: columnSortName,
                            columnSortOrder: columnSortOrder,
                            filterAgent: $('#agent').val(),
                            filterStatus: $('#status').val(),
                            filterUtm: $('#utm_source').val(),
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
                        },
                    });
                }
            }, "colvis"],
            order: [[0, 'desc']],
            'columnDefs': [ {
                targets: [ 4], visible: false
            }],
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
            ajax: "{{route('admin.report.getReport')}}",
            columns: [
                { data: 'id' },
                { data: 'agent' },
                { data: 'region' },
                { data: 'course' },
                { data: 'id' }, // заменить на поток
                { data: 'lastname' },
                { data: 'firstname' },
                { data: 'category' },
                { data: 'status' },
                { data: 'created_at' },
                { data: 'utm_source' },
                { data: 'utm_medium' },
                { data: 'utm_campaign' },
            ]
        });




        table.buttons().container().appendTo('#report_wrapper .col-md-6:eq(0)');

        $('#agent').on('change', function (e){

            table
                .column(1)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#status').on('change', function (e){

            table
                .column(8)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#utm_source').on('change', function (e){

            table
                .column(10)
                .search(this.value, {exact: true})
                .draw();
        })
    </script>
@endsection
