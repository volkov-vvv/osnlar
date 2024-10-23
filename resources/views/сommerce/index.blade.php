@extends('layouts.main2')
@section('content')

    <main class="blog">
        <div class="container">
            <h1 class="edica-page-title" data-aos="fade-up">Платные курсы</h1>
            <section class="featured-posts-section">
                <div class="row">
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
                                <p class="price">{{number_format($course->price, 0, ',', ' ')}} руб.</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>

        </div>

    </main>

@endsection
