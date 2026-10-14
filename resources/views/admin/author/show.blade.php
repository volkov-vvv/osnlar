@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $author->full_name ?: $author->name }}</h1>
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
                                    <td>{{$author->id}}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия</td>
                                    <td>{{ $author->lastname }}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{{ $author->firstname }}</td>
                                </tr>
                                <tr>
                                    <td>Отчество</td>
                                    <td>{{ $author->middlename }}</td>
                                </tr>
                                <tr>
                                    <td>Псевдоним</td>
                                    <td>{{$author->name}}</td>
                                </tr>
                                <tr>
                                    <td>Фото</td>
                                    <td>
                                        @if($author->photo)
                                            <img src="{{ url('storage/' . $author->photo) }}" alt="Фото автора" width="160" height="160" style="object-fit: cover;" class="rounded">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>{!! $author->description !!}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$author->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$author->updated_at}}</td>
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
            <div class="row pb-4">
                <div class="col-3">
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.author.edit', $author->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.author.index')}}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.author.destroy', $author->id)}}">
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
