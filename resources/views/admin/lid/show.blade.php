@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$lid->lastname . ' ' . $lid->firstname . ' ' . $lid->middlename}}</h1>
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
                <div class="col-6">
{{--                    <div class="alert alert-{{ $lid->status_id == 1 ? 'danger' : 'warning' }}" role="alert">--}}
{{--                        @foreach($statuses as $status)--}}
{{--                            {{ $status->id == $lid->status_id ? $status->title : '' }}--}}
{{--                        @endforeach--}}
{{--                    </div>--}}

                    <div class="alert @switch($lid->status_id) @case(1) {{$lid->status_id == 1 ? 'alert-danger' : ''}} @break @case(2) {{$lid->status_id == 2 ? 'alert-warning' : ''}} @break @case(3) {{$lid->status_id == 3 ? 'alert-info' : ''}}@endswitch" role="alert">
                        @foreach($statuses as $status)
                            {{$status->id == $lid->status_id ? $status->title : '' }}
                        @endforeach
                    </div>

                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>Номер заявки</td>
                                    <td>{{$lid->id}}</td>
                                </tr>
                                <tr>
                                    <td>Дата рождения</td>
                                    <td>{{$lid->data}}</td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>
                                        @foreach($courses as $course)
                                            {{ $course->id == $lid->course_id ? $course->title : '' }}
                                        @endforeach
                                    </td>

                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{$lid->email}}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{{$lid->phone}}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$lid->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$lid->updated_at}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">

                {{--                    <div class="col-1">--}}
                {{--                        <form method="post" action="{{route('admin.lid.delete', $lid->id)}}">--}}
                {{--                            @csrf--}}
                {{--                            @method('DELETE')--}}
                {{--                            <button class="btn btn-danger float-end" type="submit" disabled>Удалить</button>--}}
                {{--                        </form>--}}
                {{--                    </div>--}}
                <div class="col-2">
                    <a class="btn btn-outline-primary mr-2"
                       href="{{route('admin.lid.edit', $lid->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.lid.index')}}">Назад</a>
                </div>


            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
