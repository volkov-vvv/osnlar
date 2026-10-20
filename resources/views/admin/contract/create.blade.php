@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Создание договора</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-lg-6 px-0">
                <form action="{{ route('admin.contract.store') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="template">Шаблон договора</label>
                        <select name="template" id="template" class="form-control select2" style="width: 100%;" required>
                            <option value="">Выберите шаблон</option>
                            @foreach($templates as $template)
                                <option value="{{ $template['slug'] }}" {{ old('template') === $template['slug'] ? 'selected' : '' }}>
                                    {{ $template['title'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('template')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author_id">Автор</label>
                        <select name="author_id" id="author_id" class="form-control select2" style="width: 100%;" required>
                            <option value="">Выберите автора</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ (string) old('author_id') === (string) $author->id ? 'selected' : '' }}>
                                    {{ $author->full_name ?: $author->name }}
                                    @if($author->full_name && $author->name && $author->full_name !== $author->name)
                                        ({{ $author->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="course_id">Курс</label>
                        <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;" required>
                            <option value="">Выберите курс</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (string) old('course_id') === (string) $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="signed_at">Дата подписания договора</label>
                        <input name="signed_at" id="signed_at" type="date" class="form-control" value="{{ old('signed_at') }}" style="max-width: 16rem;">
                        @error('signed_at')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nap_registered_at">Дата регистрации плательщика налога на профессиональный доход</label>
                        <input name="nap_registered_at" id="nap_registered_at" type="date" class="form-control" value="{{ old('nap_registered_at') }}" style="max-width: 16rem;">
                        @error('nap_registered_at')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ends_at">Дата окончания договора</label>
                        <input name="ends_at" id="ends_at" type="date" class="form-control" value="{{ old('ends_at') }}" style="max-width: 16rem;">
                        @error('ends_at')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Сохранить
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.contract.index') }}">Назад</a>
                </form>
            </div>
        </div>
    </section>
@endsection
