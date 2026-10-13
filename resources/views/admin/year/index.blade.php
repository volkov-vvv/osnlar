@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Годы</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <a href="{{route('admin.year.create')}}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Год</th>
                                    <th>Активен</th>
                                    <th>По умолчанию</th>
                                    <th>Сортировка</th>
                                    <th>Дата создания</th>
                                    <th colspan="3">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($years as $year)
                                    <tr>
                                        <td>{{$year->id}}</td>
                                        <td>{{$year->value}}</td>
                                        <td>{{$year->is_active ? 'Да' : 'Нет'}}</td>
                                        <td>{{$year->is_default ? 'Да' : 'Нет'}}</td>
                                        <td>{{$year->sort}}</td>
                                        <td>{{$year->created_at}}</td>
                                        <td><a href="{{route('admin.year.show', $year->id)}}"><i class="far fa-eye"></i></a></td>
                                        <td><a href="{{route('admin.year.edit', $year->id)}}" class="text-success"><i class="fas fa-pen"></i></a></td>
                                        <td>
                                            <form method="post" action="{{route('admin.year.destroy', $year->id)}}">
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
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
