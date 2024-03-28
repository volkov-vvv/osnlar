@extends('layouts.main2')
@section('content')

    <main class="blog-post">
        <div class="container">
            <h1 class="edica-page-title" data-aos="fade-up">{{$course->title}}</h1>
            <p class="edica-blog-post-meta" data-aos="fade-up" data-aos-delay="200">Эксперты курса:
                @foreach($authors as $author)
                    {{ is_array($course->authors->pluck('id')->toArray()) && in_array($author->id, $course->authors->pluck('id')->toArray()) ? $author->name . ',' : ''  }}
                @endforeach
                старт обучения: май 2024 года
            </p>
            <section class="blog-post-featured-img" data-aos="fade-up" data-aos-delay="300">
                <img src="{{url('storage/' . $course->image) }}" alt="featured image" class="w-100">
            </section>
            <section class="post-content">
                <div class="row">
                    <div class="col-lg-9 mx-auto" data-aos="fade-up">
                       <p>{!! $course->description !!}</p>
                    </div>
                </div>


            </section>
            <div class="row">
                <div class="col-lg-9 mx-auto">

                    <section class="comment-section">
                        <h2 class="section-title mb-5" data-aos="fade-up">  </h2>

                    </section>
                </div>
            </div>
        </div>
        <section class="edica-footer-banner-section">
            <div class="container">
                <div class="footer-banner" data-aos="fade-up">
                    <h1 class="banner-title">Подать заявку!</h1>
                    <div class="banner-btns-wrapper">
                        <div class="carousel-content-btns">
                            <a href="{{route('lid.create')}}" class="btn btn-primary"><i class="fas fa-arrow-right mr-2"></i>
                                Записаться</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
