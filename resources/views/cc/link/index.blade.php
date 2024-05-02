@extends('cc.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ссылки</h1>
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
                    <a href="{{route('cc.link.create')}}" type="button" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ссылка</th>
                                    <th>Регион</th>
                                    <th>Курс</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($links as $link)
                                    <tr>
                                        <td>{{$link->id}}</td>
                                        <td>{{$link->link}}</td>
                                        <td>{{$link->region->title}}</td>
                                        <td>{{$link->course->title}}</td>
                                        <td>{{$link->created_at}}</td>
                                        <td>
                                            <a  href="{{route('cc.link.show', $link->id)}}"><i class="far fa-eye"></i></a>
                                            <a  href="{{route('cc.link.edit', $link->id)}}" class="text-success"><i class="fas fa-pen"></i></a>

                                            <form method="post" action="{{route('cc.link.destroy', $link->id)}}" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-transparent border-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>







@endsection
