@extends('layouts.main2')
@section('content')

    <main class="blog">
        <div class="container">
            <div class="row p-5"  data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center">Код будущего</h1>
                </div>
            </div>
            <section class="featured-posts-section">
                <div class="row">

                    @if(!empty($courses))
                        @foreach($courses as $course)
                            <div class="col-md-4 fetured-post blog-post" data-aos="fade-right">
                                <div class="blog-post-thumbnail-wrapper">
                                    <a href="{{route('course.show', $course->id)}}">
                                        <img src="{{'storage/' . $course->prev_img}}" alt="blog post">
                                    </a>
                                </div>
                                <p class="blog-post-category"></p>
                                <a href="{{route('course.show', $course->id)}}" class="blog-post-permalink">
                                    <h6 class="blog-post-title">{{$course->title}}</h6>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p>Здесь пока ничего нет</p>
                        <br><br><br>
                    @endif


                </div>
            </section>

        </div>

    </main>

@endsection
