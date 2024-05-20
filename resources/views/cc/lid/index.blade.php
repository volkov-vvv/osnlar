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
                                <!--
                                <table class="inputs">
                                    <tbody><tr>
                                        <td>Отвественный:</td>
                                        <td>
                                            <select id="responsible" name="responsible">
                                                <option>Все</option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->name}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    </tbody></table>
                                -->
                                <table id="example1" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Ответственный</th>
                                        <th>Курс</th>
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
                                            <td>
                                                @foreach($courses as $course)
                                                    {{ $course->id == $lid->course_id ? $course->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>{{$lid->lastname}}</td>
                                            <td>{{$lid->firstname}}</td>
                                            <td>{{$lid->email}}</td>
                                            <td>{{$lid->phone_prefix == '7' ? '8'.$lid->phone : $lid->phone_prefix.$lid->phone}}</td>
                                            <td>
                                                <span class="badge rounded-pill"
                                                      style="background-color: {{$lid->status->color}} !important">
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
@section('javascript1')
    <script>
        const responsible = document.querySelector('#responsible');
//        const maxEl = document.querySelector('#max');

        const table = new DataTable('#example1');

        // Custom range filtering function
        table.search.fixed('range', function (searchStr, data, index) {
            var min = parseInt(minEl.value, 10);
            var max = parseInt(maxEl.value, 10);
            var age = parseFloat(data[3]) || 0; // use data for the age column

            if (
                (isNaN(min) && isNaN(max)) ||
                (isNaN(min) && age <= max) ||
                (min <= age && isNaN(max)) ||
                (min <= age && age <= max)
            ) {
                return true;
            }

            return false;
        });

        // Changes to the inputs will trigger a redraw to update the table
        responsible.addEventListener('change', function () {
//            alert('!!!');
            //table.draw();
        });

    </script>
@endsection
