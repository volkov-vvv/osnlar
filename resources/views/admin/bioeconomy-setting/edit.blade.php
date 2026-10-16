@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Биоэкономика</h1>
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Документы на странице</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.bioeconomy.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                @foreach($documents as $document)
                                    <div class="mb-4 form-group">
                                        <label for="{{ $document['input'] }}">{{ $document['title'] }}</label>
                                        @if($document['path'])
                                            <div class="mb-2">
                                                <a href="{{ url('storage/' . $document['path']) }}" target="_blank" rel="noopener">
                                                    {{ basename($document['path']) }}
                                                </a>
                                            </div>
                                        @else
                                            <div class="mb-2 text-muted">Файл не загружен</div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error($document['input']) is-invalid @enderror" id="{{ $document['input'] }}" name="{{ $document['input'] }}">
                                            <label class="custom-file-label" for="{{ $document['input'] }}">Выберите файл</label>
                                        </div>
                                        <small class="form-text text-muted">pdf, doc, docx, ppt, pptx, xls, xlsx, до 128 МБ. Новый файл заменяет текущий.</small>
                                        @error($document['input'])
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @if($document['path'])
                                            <div class="custom-control custom-checkbox mt-2">
                                                <input type="checkbox" class="custom-control-input" id="remove_{{ $document['input'] }}" name="remove_{{ $document['input'] }}" value="1">
                                                <label class="custom-control-label" for="remove_{{ $document['input'] }}">Удалить текущий файл</label>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
