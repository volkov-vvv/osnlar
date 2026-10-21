@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$project->title}}</h1>
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
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{$project->id}}</td>
                                </tr>
                                <tr>
                                    <td>Название</td>
                                    <td>{{$project->title}}</td>
                                </tr>
                                <tr>
                                    <td>Краткое название</td>
                                    <td>{{ $project->short_title ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Формулировка в договоре</td>
                                    <td>{{ $project->contract_phrase ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$project->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$project->updated_at}}</td>
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
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.project.edit', $project->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.project.index')}}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.project.destroy', $project->id)}}">
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
