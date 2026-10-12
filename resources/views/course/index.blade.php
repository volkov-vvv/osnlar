@extends('layouts.main2')

@push('styles')
    <style>
        .course-page-title {
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
            line-height: 1.25;
        }
        .course-page-lead {
            color: grey;
            font-size: 20px;
        }
        @media (max-width: 767.98px) {
            .course-page-title {
                font-size: 1.6rem;
            }
            .course-page-lead {
                font-size: 16px;
            }

            .course-tabs {
                flex-direction: column;
                align-items: stretch;
                border-bottom: 0;
            }

            .course-tabs .nav-link {
                width: 100%;
                text-align: center;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
                padding: 0.75rem 1rem;
                white-space: normal;
                line-height: 1.3;
            }

            .course-tabs .nav-link.active {
                border-color: #007bff;
                background-color: #007bff;
                color: #fff;
            }
        }
    </style>
@endpush

@section('content')

    <main class="blog">
        <div class="container">

            <div class="row px-3 px-md-5 py-4 py-md-5" data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center course-page-title">Запись на бесплатное обучение</h1>
                    <p class="text-center course-page-lead">
                        В рамках реализации федерального проекта «Активные меры содействия занятости»
                    </p>
                </div>
            </div>

            <nav>
                <div class="nav nav-tabs nav-fill course-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Все</button>
                    <button class="nav-link" id="nav-digital-tab" data-toggle="tab" data-target="#nav-digital" type="button" role="tab" aria-controls="nav-digital" aria-selected="false">Профессиональная переподготовка<br> (256 ч.)</button>
                    <button class="nav-link" id="nav-designed-tab" data-toggle="tab" data-target="#nav-designed" type="button" role="tab" aria-controls="nav-designed" aria-selected="false">Повышение квалификации<br> (144 ч.)</button>
                    <button class="nav-link" id="nav-business-tab" data-toggle="tab" data-target="#nav-business" type="button" role="tab" aria-controls="nav-business" aria-selected="false">Повышение квалификации<br> (72 ч.)</button>
                </div>
            </nav>
            <div class="pt-5 tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
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
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade" id="nav-digital" role="tabpanel" aria-labelledby="nav-digital-tab" tabindex="0">
                    <section class="featured-posts-section pt-5">
                        <div class="row">
                            @foreach($coursesS1 as $course)
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
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade" id="nav-designed" role="tabpanel" aria-labelledby="nav-designed-tab" tabindex="0">
                    <section class="featured-posts-section pt-5">
                        <div class="row">
                            @foreach($coursesS2 as $course)
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
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade" id="nav-business" role="tabpanel" aria-labelledby="nav-business-tab" tabindex="0">
                    <section class="featured-posts-section pt-5">
                        <div class="row">
                            @foreach($coursesS3 as $course)
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
                        </div>
                    </section>
                </div>
            </div>



        </div>

    </main>

@endsection
