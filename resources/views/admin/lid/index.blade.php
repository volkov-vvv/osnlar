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
            <!-- Small boxes (Stat box) -->

            <div class="row mb-3">
                <div class="col">
                    <a href="{{route('admin.lid.create')}}" type="button" class="btn btn-primary"><i
                            class="fa-solid fa-plus"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                        <div class="card">

                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Отчество</th>
                                        <th>Дата рождения</th>
                                        <th>Email</th>
                                        <th>Телефон</th>
                                        <th>Дата создания</th>
                                        <th>Действия</th>
                                        <th>Удаление</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lids as $lid)
                                        <tr>
                                            <td>{{$lid->id}}</td>
                                            <td>{{$lid->lastname}}</td>
                                            <td>{{$lid->firstname}}</td>
                                            <td>{{$lid->middlename}}</td>
                                            <td>{{$lid->data}}</td>
                                            <td>{{$lid->email}}</td>
                                            <td>{{$lid->phone}}</td>
                                            <td>{{$lid->created_at}}</td>
                                            <td>
                                                <a href="{{route('admin.lid.show', $lid->id)}}"><i
                                                        class="far fa-eye"></i></a> &nbsp; &nbsp;
                                                <a href="{{route('admin.lid.edit', $lid->id)}}" class="text-success"><i
                                                        class="fas fa-pen"></i></a>
                                                {{--                                                <form method="post" action="{{route('admin.lid.delete', $lid->id)}}">--}}
                                                {{--                                                    @csrf--}}
                                                {{--                                                    @method('DELETE')--}}
                                                {{--                                                    <button class="bg-transparent border-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>--}}
                                                {{--                                                </form>--}}
                                            </td>
                                            <td>

                                                <form method="post" action="{{route('admin.lid.delete', $lid->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="bg-transparent border-0" type="submit"><i
                                                            class="fas fa-trash text-danger" role="button"></i></button>
                                                </form>
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
