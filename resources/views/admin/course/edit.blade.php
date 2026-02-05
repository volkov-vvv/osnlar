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
                    <div class="mb-3 form-group">
                        <label>Выберите компанию:</label>
                        <select name="company_id" class="select2" data-placeholder="Выберите" style="width: 100%;">
                            @foreach($companies as $company)
                                <option
                                    @if(!empty($course->company_id) && $company->id == $course->company_id)
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
                               value="{{$course->title}}">
                        @error('title')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Описание курса</label>
                        <textarea class="summernote" name="description">
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
{{--                    <div class="mb-3 form-group">--}}
{{--                        <label>Выберите категорию</label>--}}
{{--                        <select name="category_id" class="form-control">--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <option value="{{$category->id}}"--}}
{{--                                    {{ $category->id == $course->category_id ? ' selected' : '' }}--}}
{{--                                >{{$category->title}}</option>--}}
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
                                    {{ is_array($course->authors->pluck('id')->toArray()) && in_array($author->id, $course->authors->pluck('id')->toArray()) ? ' selected' : ''  }}
                                    value="{{$author->id}}">{{$author->name}}</option>
                            @endforeach
                        </select>
                        @error('author_ids')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="exampleInputFile">Обновить УТП</label>
                        <div class="mb-2">
                            <a href="{{ url('storage/' . $course->utp) }}">
                                УТП
                            </a>
                        </div>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="utp">
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

                    <div class="mb-3">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="open_registration" value="0">
                                <input name="open_registration" type="checkbox" class="custom-control-input" id="customSwitch" value="1"
                                    {{ $course->open_registration == 1 ? ' checked' : '' }}
                                >
                                <label class="custom-control-label" for="customSwitch">Открыть запись на обучение</label>
                            </div>
                            @error('open_registration')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_published" value="0">
                                    <input name="is_published" type="checkbox" class="custom-control-input" id="customSwitch2" value="1"
                                        {{ $course->is_published == 1 ? ' checked' : '' }}
                                    >
                                    <label class="custom-control-label" for="customSwitch2">Публикация</label>
                                </div>
                            @error('is_published')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="code_future" value="0">
                                <input name="code_future" type="checkbox" class="custom-control-input" id="customSwitch3" value="1"
                                    {{ $course->code_future == 1 ? ' checked' : '' }}
                                >
                                <label class="custom-control-label" for="customSwitch3">Код будущего</label>
                            </div>
                            @error('code_future')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-group">
                        <h5><b>Торговый каталог</b></h5>
                        <label>Стоимость обучения (руб.)</label>
                        <input name="price" type="text" class="form-control" aria-describedby="price"
                               value="{{$course->price}}">
                        @error('price')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3">

                            <div class="custom-control custom-switch">
                                <input type="hidden" name="fast_pay" value="0">
                                <input name="fast_pay" type="checkbox" class="custom-control-input" id="customSwitch4" value="1"
                                    {{ $course->fast_pay == 1 ? ' checked' : '' }}
                                >
                                <label class="custom-control-label" for="customSwitch4">Разрешена оплата после создания заявки</label>
                            </div>
                            @error('fast_pay')
                            <div class="text-danger">{{$message}}</div>
                            @enderror

                    </div>

                    <div class="mb-3 form-group">
                        <h5><b>SEO</b></h5>
                        <label>Title</label>
                        <input name="seo_title" type="text" class="form-control" aria-describedby="Title"
                               value="{{$course->seo_title}}">
                        @error('seo_title')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                        <label>Description</label>
                        <textarea class="form-control" name="seo_description">{{$course->seo_description}}</textarea>
                        @error('seo_description')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

{{--                    <div class="mb-3 form-group">--}}
{{--                        <label>Выберите категорию</label>--}}
{{--                        <select name="series" class="form-control">--}}
{{--                            @foreach($series as $serie)--}}
{{--                                <option value="{{$serie->id}}"--}}
{{--                                    {{ $serie->id == old('series') ? 'selected' : '' }}--}}
{{--                                >{{$serie->title}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        @error('series')--}}
{{--                        <div class="text-danger">{{$message}}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

                    <div class="mb-3">
                        <label>Серия</label>
                        <select name="series" class="form-control select2">
                            @foreach($series as $serie)
                                <option value="{{$serie->id}}"
                                    {{ $serie->id == $course->series ? 'selected' : '' }}
                                >{{$serie->title}}</option>
                            @endforeach
                        </select>
                        @error('series')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Год</label>
                        <select name="years" class="form-control select2">
                            @foreach($years as $year)
                                <option value="{{$year}}"
                                    {{ $year == $course->years ? 'selected' : '' }}
                                >{{$year}}</option>
                            @endforeach
                        </select>
                        @error('years')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>


                    <div class="mb-3 mt-5">
                        <button type="submit" class="btn btn-primary">Обновить</button>
                        <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
                    </div>

                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
