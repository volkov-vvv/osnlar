@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$region->title}}</h1>
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
                                    <td>{{$region->id}}</td>
                                </tr>
                                <tr>
                                    <td>Название</td>
                                    <td>{{$region->title}}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$region->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$region->updated_at}}</td>
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
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.region.edit', $region->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.region.index')}}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.region.delete', $region->id)}}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger float-end" type="submit">Удалить</button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-6"></div>

            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h2>Ссылки</h2>
                </div>
                <div class="col-6">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ссылка</th>
                                    <th>Курс</th>
                                    <th>Дата создания</th>
                                    <th colspan="3">Действия</th>
                                </tr>
                                </thead>
                                @foreach($region->links as $link)
                                    <tr>
                                        <td>{{$link->id}}</td>
                                        <td>{{$link->link}}</td>
                                        <td>{{$link->course->title}}</td>
                                        <td>{{$link->created_at}}</td>
                                        <td><a  href="{{route('admin.link.show', $link->id)}}"><i class="far fa-eye"></i></a></td>
                                        <td><a  href="{{route('admin.link.edit', $link->id)}}" class="text-success"><i class="fas fa-pen"></i></a></td>
                                        <td>
                                            <form method="post" action="{{route('admin.link.destroy', $link->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-transparent border-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-6"></div>
            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
