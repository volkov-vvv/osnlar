@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$year->value}}</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{$year->id}}</td>
                                </tr>
                                <tr>
                                    <td>Год</td>
                                    <td>{{$year->value}}</td>
                                </tr>
                                <tr>
                                    <td>Активен</td>
                                    <td>{{$year->is_active ? 'Да' : 'Нет'}}</td>
                                </tr>
                                <tr>
                                    <td>По умолчанию</td>
                                    <td>{{$year->is_default ? 'Да' : 'Нет'}}</td>
                                </tr>
                                <tr>
                                    <td>Сортировка</td>
                                    <td>{{$year->sort}}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$year->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$year->updated_at}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-3">
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.year.edit', $year->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.year.index')}}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.year.destroy', $year->id)}}">
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
