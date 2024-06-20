@extends('agent.layouts.main')
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

                                <table class="table table-striped mb-2">
                                    <tbody>
                                    <tr>
                                        <td>
                                            Агент:
                                            <select id="agent" name="agent">
                                                <option></option>
                                                @foreach($agentsForFilter as $agent)
                                                    <option value="{{$agent->agent->title}}">{{$agent->agent->title}}</option>
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
                                                @foreach($sourcesForFilter as $source)
                                                    @if(!empty($source->utm_source))
                                                        <option value="{{$source->utm_source}}">{{$source->utm_source}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table id="report1" class="table table-bordered table-striped hover">
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
                                    <tbody>
                                    @foreach($lids as $lid)
                                        <tr>
                                            <td>{{$lid->id}}</td>
                                            <td>
                                                {{$lid->agent->title}}
                                            </td>
                                            <td>
                                                @foreach($regions as $region)
                                                    {{ $region->id == $lid->region_id ? $region->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($courses as $course)
                                                    {{ $course->id == $lid->course_id ? $course->title : '' }}
                                                @endforeach
                                            </td>
                                            <td> </td>
                                            <td>{{$lid->lastname}}</td>
                                            <td>{{$lid->firstname}}</td>
                                            <td>
                                                @foreach($categories as $category)
                                                    {{ $category->id == $lid->category_id ? $category->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill
                                                @switch($lid->status_id)
                                                @case(1) {{$lid->status_id == 1 ? 'bg-danger' : ''}}
                                                @break
                                                @case(2) {{$lid->status_id == 2 ? 'bg-warning text-dark' : ''}}
                                                @break
                                                @case(3) {{$lid->status_id == 3 ? 'bg-info' : ''}}
                                                @break
                                                @case(4) {{$lid->status_id == 4 ? 'bg-success' : ''}}
                                                @endswitch">
                                                    @foreach($statuses as $status)
                                                        {{$status->id == $lid->status_id ? $status->title : '' }}
                                                    @endforeach
                                                </span>
                                            </td>
                                            <td>{{$lid->created_at}}</td>
                                            <td>{{$lid->utm_source}}</td>
                                            <td>{{$lid->utm_medium}}</td>
                                            <td>{{$lid->utm_campaign}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

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
        var table= new DataTable('#report1', {
            order: [[0, 'desc']],
            columnDefs: [
                { targets: [ 4], visible: false }
            ],
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "colvis"],
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

        })

        table.buttons().container().appendTo('#report1_wrapper .col-md-6:eq(0)');

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
