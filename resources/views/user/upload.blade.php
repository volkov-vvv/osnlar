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
                                            <input type="file" name="doc">
                                            <div class="mb-3 form-group">
                                                <label for="exampleInputFile">Добавить основное изображение курса</label>
                                                <div class="input-group">





                                                </div>
                                                @error('image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3 mt-5">
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                                <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
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
