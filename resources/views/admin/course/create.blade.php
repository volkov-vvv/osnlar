@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid mb-4">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Создание курса</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div  class="col-6">
                <form action="{{route('admin.course.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 form-group">
                        <label>Выберите компанию:</label>
                        <select name="company_id" class="select2" data-placeholder="Выберите" style="width: 100%;">
                            @foreach($companies as $company)
                                <option
                                    @if(!empty(old('company_id')) && $company->id == old('$company_id'))
                                     selected
                                    @endif
                                    value="{{$company->id}}">{{$company->title}}</option>
                            @endforeach
                        </select>
                        @error('author_ids')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Название курса</label>
                        <input name="title" type="text" class="form-control" aria-describedby="Название"
                        value="{{old('title')}}">
                        @error('title')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Описание курса</label>
                        <textarea class="summernote" name="description">
                            {{old('description')}}
                        </textarea>
                        @error('description')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label for="exampleInputFile">Добавить заставку курса</label>
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
{{--                    <div class="mb-3 form-group">--}}
{{--                        <label>Выберите категорию</label>--}}
{{--                        <select name="category_id" class="form-control">--}}
{{--                            @foreach($categories as $category)--}}
{{--                            <option value="{{$category->id}}"--}}
{{--                            {{ $category->id == old('category_id') ? ' selected' : '' }}--}}
{{--                            >{{$category->title}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        @error('category_id')--}}
{{--                        <div class="text-danger">{{$message}}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                    <div class="mb-3 form-group">
                        <label>Выберите автора/ов:</label>
                        <select name="author_ids[]" class="select2" multiple="multiple" data-placeholder="Выберите" style="width: 100%;">
                            @foreach($authors as $author)
                            <option
                                {{ is_array(old('author_ids')) && in_array($author->id, old('author_ids')) ? ' selected' : ''  }}
                                value="{{$author->id}}">{{$author->name}}</option>
                            @endforeach
                        </select>
                        @error('author_ids')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label for="exampleInputFile">Добавить учебно-тематический план</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="utp">
                                <label class="custom-file-label">Выберите файл</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Загрузить</span>
                            </div>
                        </div>
                        @error('utp')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <h5><b>Торговый каталог</b></h5>
                        <label>Стоимость обучения (руб.)</label>
                        <input name="price" type="text" class="form-control" aria-describedby="price"
                               value="{{old('price')}}">
                        @error('price')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <h5><b>SEO</b></h5>
                        <label>Title</label>
                        <input name="seo_title" type="text" class="form-control" aria-describedby="Title"
                               value="{{old('seo_title')}}">
                        @error('seo_title')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                        <label>Description</label>
                        <textarea class="form-control" name="seo_description">{{old('eo_description')}}</textarea>
                        @error('seo_description')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>


                    <div class="mb-3 mt-5">
                        <button type="submit" class="btn btn-primary">Создать</button>
                        <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
                    </div>


                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
