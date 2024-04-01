@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$course->title}}</h1>
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
                                    <td>{{$course->id}}</td>
                                </tr>
                                <tr>
                                    <td>Название</td>
                                    <td>{{$course->title}}</td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>
                                        <p>{!! $course->description !!}</p>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>Категория</td>--}}
{{--                                    <td>--}}
{{--                                        @foreach($categories as $category)--}}
{{--                                            {{ $category->id == $course->category_id ? $category->title : '' }}--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                                <tr>
                                    <td>Авторы</td>
                                    <td>
                                        @foreach($authors as $author)
                                            {{ is_array($course->authors->pluck('id')->toArray()) && in_array($author->id, $course->authors->pluck('id')->toArray()) ? $author->name . ',' : ''  }}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$course->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$course->updated_at}}</td>
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
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.course.edit', $course->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.course.delete', $course->id)}}">
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
