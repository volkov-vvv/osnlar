@extends('layouts.main2')
@section('content')

    <main class="blog">
        <div class="container" style="padding-bottom: 120px;">
            <div class="row p-5"  data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center">Биоэкономика</h1>
                    <p class="text-center" style="color: grey; font-size: 20px">Национальный проект «Технологическое обеспечение биоэкономики».<br>
                        Совместно с «Агентством развития профессионального мастерства» проводим бесплатное обучение по программам дополнительного профессионального образования для сотрудников передовых организаций в области биоэкономики. Наши программы (BioTech &amp; IT): управление цифровыми решениями, биоинформатика, использование ИИ для анализа данных в биологических исследованиях.</p>
                    <div class="text-center mt-4">
                        <a href="{{ route('org.bioeconomy.create') }}" class="btn btn-primary btn-lg px-5">Оставить заявку от организации</a>
                    </div>
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
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="row pb-5"  data-aos="fade-up">
                            <div class="col">
                                <p class="text-center" style="color: #3d444b; font-size: 20px">К сожалению, на данный момент запись на все наши курсы закончилась. <br> Подпишитесь на наши информационные каналы в <a href="{{ url('https://max.ru/id7751117260_biz') }}" target="_blank"><img src="{{ asset('assets/images/Max_logo_2025.png') }}" width="22" height="22" alt="Max" style="vertical-align: middle; margin-top: -2px;"> Максе</a> и <a href="{{ url('https://t.me/osnovanie_study') }}" target="_blank"><i class="fab fa-telegram"></i> Телеграмме</a>, чтобы быть в курсе ближайших стартов обучения.</p>
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        </div>
    </main>

@endsection
