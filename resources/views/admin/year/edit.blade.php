@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Обновление года: "{{$year->value}}"</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-4">
                <form action="{{route('admin.year.update', $year->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label>Год</label>
                        <input name="value" type="number" class="form-control" min="2000" max="2100" value="{{old('value', $year->value)}}">
                        @error('value')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Сортировка</label>
                        <input name="sort" type="number" class="form-control" min="0" value="{{old('sort', $year->sort)}}">
                        @error('sort')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $year->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Активен</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default', $year->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">По умолчанию</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.year.index')}}">Назад</a>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
