@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Обновление статуса: "{{$status->title}}"</h1>
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
                <form action="{{route('admin.status.update', $status->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label>Название категории</label>
                        <input name="title" type="text" class="form-control" aria-describedby="Название" value="{{$status->title}}">
                        @error('title')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Цвет</label>
                        <input name="color" type="color" list="colorList" aria-describedby="Цвет" value="{{$status->color}}">
                        @include('layouts.colorlist')
                        @error('color')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.status.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

@endsection
