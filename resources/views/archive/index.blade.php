@extends('layouts.main2')
@section('content')

    <main class="blog">
        <div class="container">
            <div class="row p-5"  data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center">Архив курсов</h1>
                    <p class="text-center" style="color: grey; font-size: 20px"></p>
                </div>
            </div>
            <section class="featured-posts-section">
                <div class="row">
                    <div class="col fetured-post blog-post" data-aos="fade-right">
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Название курса</th>
                                        <th>Год</th>
                                        <th>Статус</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <td>{{$course->id}}</td>
                                            <td>{{$course->title}}</td>
                                            <td>{{$course->years}}</td>
                                            <td>{{$course->is_published == 0 ? 'Архив' : ''}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-->
                    </div>
                </div>
            </section>

        </div>

    </main>



{{--    <main class="blog">--}}
{{--        <div class="container">--}}
{{--            <h1 class="edica-page-title" data-aos="fade-up">Курсы</h1>--}}
{{--            <section class="featured-posts-section">--}}
{{--                <div class="row">--}}
{{--                    @foreach($courses as $course)--}}
{{--                    <div class="col-md-4 fetured-post blog-post" data-aos="fade-right">--}}
{{--                        <div class="blog-post-thumbnail-wrapper">--}}
{{--                            <a href="{{route('course.show', $course->id)}}">--}}
{{--                                <img src="{{'storage/' . $course->prev_img}}" alt="blog post">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <p class="blog-post-category"></p>--}}
{{--                        <a href="{{route('course.show', $course->id)}}" class="blog-post-permalink">--}}
{{--                            <h6 class="blog-post-title">{{$course->title}}</h6>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </section>--}}

{{--        </div>--}}

{{--    </main>--}}


@endsection
