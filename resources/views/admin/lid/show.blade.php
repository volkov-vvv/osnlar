@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$lid->lastname}}</h1>
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
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{$lid->id}}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия</td>
                                    <td>{{$lid->lastname}}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{{$lid->firstname}}</td>
                                </tr>
                                <tr>
                                    <td>Отчество</td>
                                    <td>{{$lid->middlename}}</td>
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
                    <!-- /.card -->
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-3">
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.lid.edit', $lid->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.lid.index')}}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.lid.delete', $lid->id)}}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger float-end" type="submit">Удалить</button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-6"></div>

            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
