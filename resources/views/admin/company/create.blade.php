@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid mb-4">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Создание компании</h1>
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
                <form action="{{route('admin.company.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Название компании</label>
                        <input name="title" type="text" class="form-control" aria-describedby="Название"
                        value="{{old('title')}}">
                        @error('title')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Описание</label>
                        <textarea class="summernote" name="description">
                            {{old('description')}}
                        </textarea>
                    </div>
                    <div class="mb-3 mt-5">
                        <button type="submit" class="btn btn-primary">Создать</button>
                        <a class="btn btn-outline-secondary" href="{{route('admin.company.index')}}">Назад</a>
                    </div>


                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
