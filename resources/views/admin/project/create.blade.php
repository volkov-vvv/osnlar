@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Создание проекта</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div  class="col-lg-6">
                <form action="{{route('admin.project.store')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label>Название проекта</label>
                        <input name="title" type="text" class="form-control" aria-describedby="Название" value="{{ old('title') }}">
                        @error('title')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="short_title">Краткое название проекта</label>
                        <input
                            name="short_title"
                            id="short_title"
                            type="text"
                            class="form-control text-uppercase"
                            maxlength="5"
                            style="max-width: 8rem; text-transform: uppercase;"
                            value="{{ old('short_title') }}"
                            placeholder="НАПР."
                        >
                        <small class="form-text text-muted">Не более 5 символов, всегда в верхнем регистре.</small>
                        @error('short_title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="contract_phrase">Формулировка в договоре</label>
                        <textarea name="contract_phrase" id="contract_phrase" class="form-control" rows="3" placeholder="Например: в рамках реализации Федерального проекта…">{{ old('contract_phrase') }}</textarea>
                        <small class="form-text text-muted">Текст после «(далее – Программа)» в PDF договора. Можно оставить пустым.</small>
                        @error('contract_phrase')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.project.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>






@endsection
