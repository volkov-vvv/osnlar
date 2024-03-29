@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Обновление курса "{{$course->title}}"</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mb-4">
            <!-- Small boxes (Stat box) -->
            <div  class="col-6">
                <form action="{{route('admin.course.update', $course->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label>Название курса</label>
                        <input name="title" type="text" class="form-control" aria-describedby="Название"
                               value="{{$course->title}}">
                        @error('title')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Описание курса</label>
                        <textarea id="summernote" name="description">
                            {{$course->description}}
                        </textarea>
                        @error('description')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label for="exampleInputFile">Добавить заставку курса</label>
                        <div class="mb-2">
                            <img src="{{ url('storage/' . $course->prev_img) }}" class="w-50">
                        </div>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="prev_img">
                                <label class="custom-file-label">Выберите файл</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Загрузить</span>
                            </div>
                        </div>
                        @error('prev_img')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label for="exampleInputFile">Добавить основное изображение курса</label>
                        <div class="mb-2">
                            <img src="{{ url('storage/' . $course->image) }}" class="w-50">
                        </div>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image">
                                <label class="custom-file-label">Выберите файл</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Загрузить</span>
                            </div>
                        </div>
                        @error('image')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Выберите категорию</label>
                        <select name="category_id" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}"
                                    {{ $category->id == $course->category_id ? ' selected' : '' }}
                                >{{$category->title}}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Выберите автора/ов:</label>
                        <select name="author_ids[]" class="select2" multiple="multiple" data-placeholder="Выберите" style="width: 100%;">
                            @foreach($authors as $author)
                                <option
                                    {{ is_array($course->authors->pluck('id')->toArray()) && in_array($author->id, $course->authors->pluck('id')->toArray()) ? ' selected' : ''  }}
                                    value="{{$author->id}}">{{$author->name}}</option>
                            @endforeach
                        </select>
                        @error('author_ids')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
