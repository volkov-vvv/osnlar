@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование компании "{{$company->title}}"</h1>
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
                <form action="{{route('admin.company.update', $company->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label>Название курса</label>
                        <input name="title" type="text" class="form-control" aria-describedby="Название"
                               value="{{$company->title}}">
                        @error('title')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Описание курса</label>
                        <textarea class="summernote" name="description">
                            {{$company->description}}
                        </textarea>
                        @error('description')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 mt-5">
                        <button type="submit" class="btn btn-primary">Обновить</button>
                        <a class="btn btn-outline-secondary" href="{{route('admin.company.index')}}">Назад</a>
                    </div>

                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
