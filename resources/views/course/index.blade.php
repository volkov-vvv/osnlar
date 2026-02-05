@extends('layouts.main2')
@section('content')

    <main class="blog">
        <div class="container">

            <div class="row p-5"  data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center">Запись на бесплатное обучение</h1>
{{--                    <div class="d-none d-lg-block"><p class="text-center" style="color: grey; font-size: 20px">В рамках реализации федерального проекта «Активные меры содействия занятости»<br> национального проекта «Кадры». Предварительная запись на обучение в 2025 году</p>--}}
{{--                    </div>--}}
                    <div class="d-none d-lg-block"><p class="text-center" style="color: grey; font-size: 20px">Федеральный проект «Активные меры содействия занятости»</p>
                    </div>
{{--                    <p class="text-center" style="color: #3d444b; font-size: 20px">Оставьте заявку и пройдите обучение в числе первых.--}}
{{--                        <br> Подпишитесь на наш <a href="{{url('https://t.me/osnovanie_study')}}" target="_blank"><i class="fab fa-telegram"></i> Телеграм канал</a>, чтобы оставаться с нами на связи.</p>--}}

                    <div class="d-lg-none pb-2"><p class="text-center" data-aos="fade-up" style="color: grey">В рамках реализации федерального проекта «Активные меры содействия занятости»</p></div>
                </div>
            </div>

            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Все</button>
                    <button class="nav-link" id="nav-digital-tab" data-bs-toggle="tab" data-bs-target="#nav-digital" type="button" role="tab" aria-controls="nav-digital" aria-selected="false">Профессиональная переподготовка (256 ч.)</button>
                    <button class="nav-link" id="nav-designed-tab" data-bs-toggle="tab" data-bs-target="#nav-designed" type="button" role="tab" aria-controls="nav-designed" aria-selected="false">Повышение квалификации (144 ч.)</button>
                    <button class="nav-link" id="nav-business-tab" data-bs-toggle="tab" data-bs-target="#nav-business" type="button" role="tab" aria-controls="nav-business" aria-selected="false">Повышение квалификации (72 ч.)</button>
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
