@extends('layouts.main2')

@push('styles')
    <style>
        .commerce-page-title {
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
            line-height: 1.25;
        }
        .commerce-page-lead {
            color: grey;
            font-size: 20px;
        }
        @media (max-width: 767.98px) {
            .commerce-page-title {
                font-size: 1.6rem;
            }
            .commerce-page-lead {
                font-size: 16px;
            }
        }
        .featured-posts-section .course-study-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 10px;
        }
        .featured-posts-section .course-study-form {
            margin: 0;
            color: #094ca1;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .featured-posts-section .course-study-form i {
            margin-right: 0.4rem;
            color: #094ca1;
        }
        .featured-posts-section .course-study-hours {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin: 0 0 0 auto;
            padding: 0.28rem 0.7rem;
            border: 1px solid rgba(9, 76, 161, 0.18);
            border-radius: 999px;
            background: linear-gradient(135deg, #eaf2fb 0%, #f7fbff 100%);
            color: #094ca1;
            font-size: 0.875rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.01em;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(9, 76, 161, 0.08);
        }
        .featured-posts-section .course-study-hours i {
            font-size: 0.8em;
            color: #094ca1;
            opacity: 0.9;
        }
        .featured-posts-section .blog-post-title {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .featured-posts-section .commerce-price {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 0.35rem 0.45rem;
            margin: 0.65rem 0 0;
            padding: 0.45rem 0.85rem;
            border: 1px solid rgba(31, 138, 66, 0.2);
            border-radius: 999px;
            background: linear-gradient(135deg, #e5f6ea 0%, #f3fbf5 100%);
            color: #3f6b4d;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.3;
            box-shadow: 0 2px 8px rgba(31, 138, 66, 0.08);
        }
        .featured-posts-section .commerce-price__amount {
            color: #1b7a3a;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }
        .featured-posts-section .commerce-price__note {
            color: #5a7d64;
            font-weight: 500;
        }
    </style>
@endpush

@section('content')

    <main class="blog">
        <div class="container">
            <div class="row px-3 px-md-5 py-4 py-md-5" data-aos="fade-up">
                <div class="col">
                    <div class="text-center mb-3">
                        <img src="{{asset('assets/images/mosfuture2.png')}}" class="img-fluid" alt="Профессии будущего">
                    </div>
                    <h1 class="text-center commerce-page-title">Софинансирование обучения</h1>
                    <p class="text-center commerce-page-lead">
                        Предварительная запись на обучение в рамках проекта «Профессии Будущего»<br>
                        программа профессионального обучения и дополнительного профессионального образования, реализуемая на условиях софинансирования при государственной поддержке.
                    </p>
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
                            @if($course->studyFormLabel() || $course->studyHoursLabel())
                                <div class="course-study-meta">
                                    @if($course->studyFormLabel())
                                        <p class="course-study-form"><i class="fas fa-graduation-cap" aria-hidden="true"></i>{{ $course->studyFormLabel() }}</p>
                                    @endif
                                    @if($course->studyHoursLabel())
                                        <p class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</p>
                                    @endif
                                </div>
                            @endif
                            <a href="{{route('course.show', $course->id)}}" class="blog-post-permalink">
                                <h6 class="blog-post-title" title="{{ $course->title }}">{{ $course->title }}</h6>
                                <p class="commerce-price">
                                    <span>Единоразово</span>
                                    <span class="commerce-price__amount">{{ number_format($course->price, 0, ',', ' ') }}&nbsp;руб.</span>
                                    <span class="commerce-price__note">или в рассрочку</span>
                                </p>
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
