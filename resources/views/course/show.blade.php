@extends('layouts.main2')
@section('content')

    <main class="blog-post">
        <div class="container">
            <h1 class="edica-page-title" data-aos="fade-up" style="color: #094ca1">{{$course->title}}</h1>
            <p class="edica-blog-post-meta" data-aos="fade-up" data-aos-delay="200">Эксперты курса:
                @foreach($authors as $author)
                    {{ is_array($course->authors->pluck('id')->toArray()) && in_array($author->id, $course->authors->pluck('id')->toArray()) ? $author->name . ',' : ''  }}
                @endforeach

            </p>
            <section class="blog-post-featured-img" data-aos="fade-up" data-aos-delay="300">
                <img src="{{url('storage/' . $course->image) }}" alt="featured image" class="w-100">

                <div class="row">
                    <div class="col pt-5 text-center"><a
                            href="{{route('lid.create', ['selectedCourse' => $course->id])}}"
                            class="btn btn-primary btn-lg"><i class="fas fa-arrow-right mr-2"></i>
                            Записаться</a></div>
                </div>
            </section>
            <section class="blog-post-featured-img" data-aos="fade-up" data-aos-delay="300">

                <div class="row">
                    <div class="col-lg-9 mx-auto" data-aos="fade-up">
                        <p>{!! $course->description !!}</p>
                    </div>
                </div>
                <div class="row pb-1">
                    <div class="col-lg-9 mx-auto" data-aos="fade-up">
                        <div class="alert alert-secondary" role="alert">
                            <p class="text-center" style="margin-bottom: 0;">
                                <a href="{{ url('storage/' . $course->utp) }}" style="color: darkblue" target="_blank">
                                    Учебно-тематический план</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row pb-5">
                    <div class="col pt-5 text-center"><a
                            href="{{route('lid.create', ['selectedCourse' => $course->id])}}"
                            class="btn btn-primary btn-lg"><i class="fas fa-arrow-right mr-2"></i>
                            Записаться</a></div>
                </div>
                <div class="row">
                    <div class="col-lg-9 mx-auto" data-aos="fade-up">
                        <div class="col"><img src="{{asset('assets/images/graf_edu.png')}}" alt="featured image"
                                              class="w-100"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" data-aos="fade-up"><img src="{{asset('assets/images/graf_edu1.png')}}"
                                                             alt="featured image" class="w-100"></div>
                    <div class="col-sm-6" data-aos="fade-up"><img src="{{asset('assets/images/graf_edu2.png')}}"
                                                             alt="featured image" class="w-100"></div>
                </div>
                <div class="row pt-5">
                    <div class="blog-post-featured-img" data-aos="fade-up">
                        <img src="{{asset('assets/images/tools.png')}}" class="w-100">
                        <img src="{{asset('assets/images/tools1.png')}}" class="w-100">
                    </div>
                </div>


            </section>

            <div class="row">
                <div class="col-lg-9 mx-auto">

                    <section class="comment-section">
                        <h2 class="section-title mb-5" data-aos="fade-up"></h2>

                    </section>
                </div>
            </div>
        </div>
        <section class="edica-footer-banner-section">
            <div class="container">
                <div class="footer-banner" data-aos="fade-up">
                    <h2 class="banner-title">Подать заявку!</h2>
                    <div class="banner-btns-wrapper">
                        <div class="carousel-content-btns">
                            <a href="{{route('lid.create', ['selectedCourse' => $course->id])}}"
                               class="btn btn-primary"><i class="fas fa-arrow-right mr-2"></i>
                                Записаться</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
