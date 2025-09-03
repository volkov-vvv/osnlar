@extends('layouts.main2')
@section('content')

    <main class="blog">
        <div class="container">
            <div class="row p-5"  data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center">Платные курсы</h1>
                    <p class="text-center" style="color: grey; font-size: 20px">Предварительная запись на обучение</p>
                </div>
            </div>
            <section class="featured-posts-section">
                <div class="row">

                    @if(count($courses) != 0)
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
                                <p class="price">Единоразово {{number_format($course->price, 0, ',', ' ')}} руб. или в рассрочу</p>
                            </a>
                        </div>
                    @endforeach
                    @else
                        <div class="row pb-5"  data-aos="fade-up">
                            <div class="col">
                                <p class="text-center" style="color: #3d444b; font-size: 20px">К сожалению, на данный момент запись на все наши курсы закончилась. <br> Подпишитесь на наш <a href="{{url('https://t.me/osnovanie_study')}}" target="_blank"><i class="fab fa-telegram"></i> Телеграм канал</a>, чтобы быть в курсе ближайших стартов обучения.</p>
                                <br>
                                <img src="{{asset('assets/images/sunset.jpg')}}" class="img-fluid" >
                                <br>
                                <br>
                                <br>
                                <br>
                            </div>
                        </div>
                    @endif


                </div>
            </section>

        </div>

    </main>

@endsection
