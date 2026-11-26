@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Темы</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Сезонные темы</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.themes.update') }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="new_year_theme_enabled" value="0">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="new_year_theme_enabled"
                                            name="new_year_theme_enabled"
                                            value="1"
                                            {{ $newYearThemeEnabled ? 'checked' : '' }}
                                        >
                                        <label class="custom-control-label" for="new_year_theme_enabled">
                                            Новогодняя тема
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Снег на сайте и гирлянда на блоке основного меню.
                                    </small>
                                    @error('new_year_theme_enabled')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
