@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Обновление сведений об организации</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div  class="col">
                <form action="{{route('admin.about.update', $about->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label>Основные сведения</label>
                        <textarea class="summernote" name="about_main">
                            {{$about->about_main}}
                        </textarea>
                        @error('about_main')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Руководство. Педагогический (научно-педагогический) состав</label>
                        <textarea class="summernote" name="about_management">
                            {{$about->about_management}}
                        </textarea>
                        @error('about_management')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Материально-техническое обеспечение и оснащенность. Доступная среда</label>
                        <textarea class="summernote" name="about_computers">
                            {{$about->about_computers}}
                        </textarea>
                        @error('about_computers')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Доступная среда</label>
                        <textarea class="summernote" name="about_all">
                            {{$about->about_all}}
                        </textarea>
                        @error('about_all')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Деятельность в ИТ</label>
                        <textarea class="summernote" name="about_it">
                            {{$about->about_it}}
                        </textarea>
                        @error('about_it')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Услуги</label>
                        <textarea class="summernote" name="about_services">
                            {{$about->about_services}}
                        </textarea>
                        @error('about_services')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Технические средства</label>
                        <textarea class="summernote" name="about_tech">
                            {{$about->about_tech}}
                        </textarea>
                        @error('about_tech')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Вакантные места для приема и перевода обучающихся</label>
                        <textarea class="summernote" name="about_admissions">
                            {{$about->about_admissions}}
                        </textarea>
                        @error('about_admissions')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Организация питания в образовательной организации</label>
                        <textarea class="summernote" name="about_catering">
                            {{$about->about_catering}}
                        </textarea>
                        @error('about_catering')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Международной сотрудничество</label>
                        <textarea class="summernote" name="about_global">
                            {{$about->about_global}}
                        </textarea>
                        @error('about_global')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Финансово-хозяйственная деятельность</label>
                        <textarea class="summernote" name="about_financial">
                            {{$about->about_financial}}
                        </textarea>
                        @error('about_financial')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Платные образовательные услуги</label>
                        <textarea class="summernote" name="about_tuition">
                            {{$about->about_tuition}}
                        </textarea>
                        @error('about_tuition')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Образование</label>
                        <textarea class="summernote" name="about_education">
                            {{$about->about_education}}
                        </textarea>
                        @error('about_education')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Стипендии и меры поддержки обучающихся</label>
                        <textarea class="summernote" name="about_grants">
                            {{$about->about_grants}}
                        </textarea>
                        @error('about_grants')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>


                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.about.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
