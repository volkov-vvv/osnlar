@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование автора: "{{ $author->full_name ?: $author->name }}"</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-8 pb-4">
                <form action="{{route('admin.author.update', $author->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                     @include('admin.author._photo', ['photo' => $author->photo])

                    <div class="mb-3">
                        <label>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" value="{{ old('lastname', $author->lastname) }}">
                        @error('lastname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Имя</label>
                        <input name="firstname" type="text" class="form-control" value="{{ old('firstname', $author->firstname) }}">
                        @error('firstname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Отчество</label>
                        <input name="middlename" type="text" class="form-control" value="{{ old('middlename', $author->middlename) }}">
                        @error('middlename')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Псевдоним</label>
                        <input name="name" type="text" class="form-control" aria-describedby="Название" value="{{ old('name', $author->name) }}">
                        @error('name')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Описание</label>
                        <textarea class="summernote" name="description">{{ old('description', $author->description) }}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                   
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.author.index')}}">Назад</a>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('javascript')
    @include('admin.author._photo_script')
@endsection
