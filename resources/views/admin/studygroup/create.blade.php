@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Создание учебной группы</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-6">
                <form action="{{ route('admin.studygroup.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label>Полное название</label>
                        <input name="title" type="text" class="form-control" value="{{ old('title') }}">
                        @error('title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Краткое название (для АРПМ)</label>
                        <input name="short_title" type="text" class="form-control" value="{{ old('short_title') }}">
                        @error('short_title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="course_id">Курс</label>
                        <select name="course_id" id="course_id" class="form-control select2" data-placeholder="Выберите курс" style="width: 100%;">
                            <option value=""></option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (string) old('course_id') === (string) $course->id ? 'selected' : '' }}>
                                    {{ $course->short_title ? $course->short_title.' — ' : '' }}{{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Описание</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3 align-items-end">
                        <div class="col-md-3">
                            <label for="starts_at">Дата начала обучения</label>
                            <input name="starts_at" id="starts_at" type="date" class="form-control" value="{{ old('starts_at') }}">
                            @error('starts_at')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="ends_at">Дата завершения обучения</label>
                            <input name="ends_at" id="ends_at" type="date" class="form-control" value="{{ old('ends_at') }}">
                            @error('ends_at')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="studygroup-duration">Срок обучения</label>
                            <input id="studygroup-duration" type="text" class="form-control" value="—" disabled readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="studygroup-workdays">Кол-во рабочих дней</label>
                            <input id="studygroup-workdays" type="text" class="form-control" value="—" disabled readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.studygroup.index') }}">Назад</a>
                </form>
            </div>
        </div>
    </section>
@endsection

@include('admin.studygroup._duration_script')
