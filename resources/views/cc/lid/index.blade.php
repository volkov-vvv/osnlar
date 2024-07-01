@extends('cc.layouts.main')
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

                                <table id="example1" class="table table-bordered table-striped hover">
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
                                    <tbody>
                                    @foreach($lids as $lid)
                                        <tr>
                                            <td>{{$lid->id}}</td>
                                            <td>
                                                @foreach($users as $user)
                                                    {{ $user->id == $lid->responsible_id ? $user->name : '' }}
                                                @endforeach
                                            </td>
                                            <td>{{isset($lid->agent->title) ? $lid->agent->title : ''}}</td>
                                            <td>
                                                @foreach($courses as $course)
                                                    {{ $course->id == $lid->course_id ? $course->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($regions as $region)
                                                    {{ $region->id == $lid->region_id ? $region->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>{{$lid->lastname}}</td>
                                            <td>{{$lid->firstname}}</td>
                                            <td>{{$lid->email}}</td>
                                            <td>{{$lid->phone_prefix == '7' ? '8'.$lid->phone : $lid->phone_prefix.$lid->phone}}</td>
                                            <td>
                                                <span class="badge rounded-pill" style="background-color: {{$lid->status->color}} !important; color:{{contrast_color($lid->status->color)}}">
                                                    {{$lid->status->title}}
                                                </span>
                                            </td>
                                            <td>{{$lid->interval}}</td>
                                            <td>{{$lid->created_at}}</td>
                                            <td>
                                                <a href="{{route('cc.lid.show', $lid->id)}}"><i
                                                        class="far fa-eye"></i></a> &nbsp; &nbsp;
                                                <a href="{{route('cc.lid.edit', $lid->id)}}" class="text-success"><i
                                                        class="fas fa-pen"></i></a>
                                                {{--                                                <form method="post" action="{{route('cc.lid.delete', $lid->id)}}">--}}
                                                {{--                                                    @csrf--}}
                                                {{--                                                    @method('DELETE')--}}
                                                {{--                                                    <button class="bg-transparent border-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>--}}
                                                {{--                                                </form>--}}
                                            </td>
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

        var table= new DataTable('#example1', {
            order: [[0, 'desc']],
            columnDefs: [
                { targets: [ 2], visible: false }
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

        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');



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

    </script>
@endsection
