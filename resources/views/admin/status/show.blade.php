@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$status->title}}</h1>
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
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{$status->id}}</td>
                                </tr>
                                <tr>
                                    <td>Название</td>
                                    <td>{{$status->title}}</td>
                                </tr>
                                <tr>
                                    <td>Цвет</td>
                                    <td>
                                        <div class="status-color" style="background-color: {{$status->color}}"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>{{$status->description}}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$status->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$status->updated_at}}</td>
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
                <div class="col">
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.status.edit', $status->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.status.index')}}">Назад</a>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.status.delete', $status->id)}}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger float-end" type="submit">Удалить</button>
                            </form>
                        </div>
                    </div>

                </div>


            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
