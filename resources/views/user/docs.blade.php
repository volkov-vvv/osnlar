@extends('layouts.main2')
@section('content')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <div class="row pt-5 pb-5">
        <div class="col text-center">
            <h1>Загрузка документов по заказу №</h1>
        </div>
    </div>

    <main class="blog">
        <div class="container">
            <section class="featured-posts-section">
                <div class="row">
                    <div class="col fetured-post blog-post" data-aos="fade-right">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <form action="{{route('user.upload.store')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{$order->id}}">


                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
                                                    <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04">Button</button>
                                                </div>
                                            </div>

                                            @foreach($needDocs as $key => $needDoc)
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputFile">
                                                        {{$needDoc['title']}}
                                                    </label>
                                                    @if(!empty($docs[$key]))
                                                        <div class="mb-2">
                                                            <a href="{{ url('storage/' . $docs[$key]['file']) }}" target="_blank">
                                                                <img src="{{asset('assets/images/' . $docs[$key]['ext'] . '.svg')}}">
                                                                {{$docs[$key]['title']}}
                                                            </a>
                                                        </div>
                                                    @endif

                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="doc[{{$key}}]">
                                                            <label class="custom-file-label">Выберите файл</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Загрузить</span>
                                                        </div>
                                                    </div>
                                                    @error('doc')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            <div class="mb-3 form-group">
                                                <label for="exampleInputFile">
                                                    Загрузите согласие на обработку персональных данных
                                                </label>
                                                @if(!empty($docs['soglasie']))
                                                    <div class="mb-2">
                                                        <a href="{{ url('storage/' . $docs['soglasie']['file']) }}" target="_blank">
                                                            <img src="{{asset('assets/images/' . $docs['soglasie']['ext'] . '.svg')}}">
                                                            {{$docs['soglasie']['title']}}
                                                        </a>
                                                    </div>
                                                @endif

                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="doc[soglasie]">
                                                        <label class="custom-file-label">Выберите файл</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Загрузить</span>
                                                    </div>
                                                </div>
                                                @error('doc')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 mt-5 col-6">
                                                    <button type="submit" name="action" value="save" class="btn btn-primary">Сохранить</button>
                                                    <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
                                                </div>
                                                <div class="mb-3 mt-5 col-6">
                                                    <button type="submit" name="action" value="first_step_done" class="btn btn-primary">Отправить на проверку</button>
                                                </div>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-->
                    </div>
                </div>
            </section>

        </div>

    </main>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <script>
        // Get a reference to the file input element
        //const inputElement = document.querySelector('input[type="file"]');

        // Create a FilePond instance
        //const pond = FilePond.create(inputElement);
    </script>
@endsection
