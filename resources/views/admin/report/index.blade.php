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

                                <table class="table mb-2">
                                    <tbody>
                                    <tr>
                                        <td>
                                            Год:
                                            <select id="year" name="year" class="form-control form-control-sm">
                                                <option value="2026">2026</option>
                                                <option value="2025">2025</option>
                                                <option value="2024">2024</option>
                                            </select>
                                        </td>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Дата:
                                            <input id="date" type="date">
                                        </td>
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
                                                @foreach($utmFilter['sources'] as $item)
                                                    <option value="{{$item->utm_source}}">{{$item->utm_source}}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            utm_medium:
                                            <select id="utm_medium" name="utm_medium">
                                                <option></option>
                                                @foreach($utmFilter['medium'] as $item)
                                                    <option value="{{$item->utm_medium}}">
                                                        {{ mb_strlen($item->utm_medium) > 20 ? mb_substr($item->utm_medium, 0, 20) . '...' : $item->utm_medium }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            utm_campaign:
                                            <select id="utm_campaign" name="utm_campaign">
                                                <option></option>
                                                @foreach($utmFilter['campaign'] as $item)
                                                    <option value="{{$item->utm_campaign}}">
                                                        {{ mb_strlen($item->utm_campaign) > 20 ? mb_substr($item->utm_campaign, 0, 20) . '...' : $item->utm_campaign }}
                                                    </option>
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
                                        <th>Email</th>
                                        <th>Категория</th>
                                        <th>Статус</th>
                                        <th>Дата создания</th>
                                        <th>utm_source</th>
                                        <th>utm_medium</th>
                                        <th>utm_campaign</th>
                                        <th>Год</th>
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
                            filterDate: $('#date').val(),
                            filterAgent: $('#agent').val(),
                            filterStatus: $('#status').val(),
                            filterUtmSource: $('#utm_source').val(),
                            filterUtmMedium: $('#utm_medium').val(),
                            filterUtmCampaign: $('#utm_campaign').val(),
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
                        },
                    });
                }
            }, "colvis"],
            order: [[0, 'desc']],
            'columnDefs': [ {
                targets: [ 4, 7], visible: false
            }],
            "language": {
                info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                paginate: {
                    "first": "Первая",
                    "previous": "<<",
                    "next": ">>",
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
                { data: 'email' },
                { data: 'category' },
                { data: 'status' },
                { data: 'created_at' },
                { data: 'utm_source' },
                { data: 'utm_medium' },
                { data: 'utm_campaign' },
                { data: 'year' },
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
                .column(9)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#utm_source').on('change', function (e){

            table
                .column(11)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#utm_medium').on('change', function (e){

            table
                .column(12)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#utm_campaign').on('change', function (e){

            table
                .column(13)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#date').on('change', function (e){

            table
                .column(10)
                .search(this.value, {exact: true})
                .draw();
        })

        $('#year').on('change', function (e){

            table
                .column(14)
                .search(this.value, {exact: true})
                .draw();
        })
    </script>
@endsection
