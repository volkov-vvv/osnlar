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
                            <table class="table text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{$course->id}}</td>
                                </tr>
                                <tr>
                                    <td>Название</td>
                                    <td>{{$course->title}}</td>
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
                                    <td>Статус по умолчанию</td>
                                    <td>{{ $course->defaultStatus->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Ближайший старт обучения</td>
                                    <td>{{ $course->starts_at ? $course->starts_at->locale('ru')->translatedFormat('j F Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Форма обучения</td>
                                    <td>{{ $course->studyFormLabel() ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Сроки обучения</td>
                                    <td>{{ $course->studyWeeksLabel() ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Учебные часы</td>
                                    <td>{{ $course->studyHoursLabel() ?? '—' }}</td>
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
            <div class="row pb-4">
                <div class="col-3">
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.course.edit', $course->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <div>
                            <form method="post" action="{{route('admin.course.destroy', $course->id)}}">
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
