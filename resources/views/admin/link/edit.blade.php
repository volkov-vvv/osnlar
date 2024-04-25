@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Редактирование ссылки: "{{$link->link}}"</h1>
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
                <form action="{{route('admin.link.update', $link->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3 form-group">
                        <label>Регион</label>
                        <select name="region_id" class="form-control">
                            @foreach($regions as $region)
                                <option value="{{$region->id}}"
                                    {{ $region->id == $link->region_id ? ' selected' : '' }}
                                >{{$region->title}}</option>
                            @endforeach
                        </select>
                        @error('region_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Курс</label>
                        <select name="course_id" class="form-control">
                            @foreach($courses as $course)
                                <option value="{{$course->id}}"
                                    {{ $course->id == $link->course_id ? ' selected' : '' }}
                                >{{$course->title}}</option>
                            @endforeach
                        </select>
                        @error('course_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Ссылка</label>
                        <input name="link" type="text" class="form-control" aria-describedby="Ссылка" value="{{$link->link}}">
                        @error('link')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>


                    <input type="hidden" name="comment" id="comment">

                    <button type="submit" id="link-edit-submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.link.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

@endsection
