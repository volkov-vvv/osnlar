@extends('layouts.main2')

@push('styles')
    <style>
        .bioeconomy-hero {
            padding: 2.5rem 1rem 3rem;
            text-align: center;
        }
        .bioeconomy-logo {
            display: block;
            width: min(280px, 78%);
            height: auto;
            margin: 0 auto 1.5rem;
        }
        .bioeconomy-lead {
            color: grey;
            font-size: 20px;
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
        .bioeconomy-apply {
            padding: 0.5rem 0 11rem;
        }
        .bioeconomy-apply-card {
            padding: 2.25rem 1.5rem;
            text-align: center;
            border: 1px solid #c5e6ce;
            border-radius: 1rem;
            background: linear-gradient(135deg, #e5f6ea 0%, #f3fbf5 55%, #ffffff 100%);
        }
        .bioeconomy-apply-title {
            margin: 0 0 1.25rem;
            color: #1b7a3a;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .bioeconomy-apply-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 14rem;
            padding: 0.9rem 1.75rem;
            border-radius: 0.8rem;
            background: #1f8a42;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(31, 138, 66, 0.22);
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
        }
        .bioeconomy-apply-btn i {
            margin-right: 0.6rem;
            color: #fff;
            font-size: 0.95em;
        }
        .bioeconomy-apply-btn:hover,
        .bioeconomy-apply-btn:focus {
            background: #176b33;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 8px 16px rgba(23, 107, 51, 0.28);
        }
        @media (max-width: 575.98px) {
            .bioeconomy-hero {
                padding: 1.75rem 0 2.5rem;
            }
            .bioeconomy-lead {
                font-size: 16px;
            }
            .bioeconomy-apply-title {
                font-size: 1.4rem;
            }
            .bioeconomy-apply-btn {
                width: 100%;
                max-width: 22rem;
            }
        }
        .bioeconomy-docs {
            display: flex;
            align-items: stretch;
            gap: 1rem;
            margin: 0 auto 2.5rem;
        }
        .bioeconomy-doc {
            display: flex;
            align-items: center;
            flex: 1 1 0;
            min-width: 0;
            margin: 0;
            padding: 1.1rem 1.15rem;
            border: 1px solid #e3e6ea;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f3f4f6 0%, #f8f9fa 60%, #ffffff 100%);
            color: inherit;
            text-decoration: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .bioeconomy-doc:hover,
        .bioeconomy-doc:focus {
            color: inherit;
            text-decoration: none;
            border-color: #1f8a42;
            box-shadow: 0 6px 18px rgba(31, 138, 66, 0.16);
        }
        .bioeconomy-doc-icon {
            flex: 0 0 3.5rem;
            width: 3.5rem;
            height: 3.5rem;
            margin-right: 1rem;
            border-radius: 50%;
            background: #fff;
            color: #094ca1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            box-shadow: 0 4px 14px rgba(9, 76, 161, 0.12);
        }
        .bioeconomy-doc-title {
            color: #094ca1;
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.25;
        }
        @media (max-width: 767.98px) {
            .bioeconomy-docs {
                flex-direction: column;
            }
            .bioeconomy-doc {
                padding: 1rem;
            }
            .bioeconomy-doc-title {
                font-size: 1.05rem;
            }
        }
    </style>
@endpush

@section('content')

    <main class="blog">
        <div class="container">
            <div class="row" data-aos="fade-up">
                <div class="col bioeconomy-hero">
                    <img class="bioeconomy-logo" src="{{ asset('assets/images/биоэкономика-thumb.jpg') }}" alt="Национальный проект «Биоэкономика»">
                    <h1>Национальный проект </br>«Технологическое обеспечение биоэкономики»</h1>
                    <p class="bioeconomy-lead">Совместно с «Агентством развития профессионального мастерства» проводим бесплатное обучение по программам дополнительного профессионального образования для сотрудников передовых организаций в области биоэкономики</p>
                </div>
            </div>
            @if($documents->isNotEmpty())
                <div class="bioeconomy-docs">
                    @foreach($documents as $document)
                        <a class="bioeconomy-doc" href="{{ url('storage/' . $document['path']) }}" target="_blank" rel="noopener" data-aos="fade-up">
                            <div class="bioeconomy-doc-icon" aria-hidden="true"><i class="{{ $document['icon'] }}"></i></div>
                            <div class="bioeconomy-doc-title">{{ $document['title'] }}</div>
                        </a>
                    @endforeach
                </div>
            @endif
            <section class="featured-posts-section edica-landing-section edica-landing-blog">
                @if(count($courses) != 0)
                    <h4 class="edica-landing-section-subtitle-alt">Доступные курсы в рамках проекта</h4>
                    <div class="row">
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
                                    <h6 class="blog-post-title">{{$course->title}}</h6>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row pb-5" data-aos="fade-up">
                        <div class="col">
                            <p class="text-center" style="color: #3d444b; font-size: 20px">К сожалению, на данный момент запись на все наши курсы закончилась. <br> Подпишитесь на наши информационные каналы в <a href="{{ url('https://max.ru/id7751117260_biz') }}" target="_blank"><img src="{{ asset('assets/images/Max_logo_2025.png') }}" width="22" height="22" alt="Max" style="vertical-align: middle; margin-top: -2px;"> Максе</a> и <a href="{{ url('https://t.me/osnovanie_study') }}" target="_blank"><i class="fab fa-telegram"></i> Телеграмме</a>, чтобы быть в курсе ближайших стартов обучения.</p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
        <section class="bioeconomy-apply">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 mx-auto">
                        <div class="bioeconomy-apply-card" data-aos="fade-up">
                            <h2 class="bioeconomy-apply-title">Подать заявку!</h2>
                            <a href="{{ route('org.bioeconomy.create') }}" class="bioeconomy-apply-btn"><i class="fas fa-pencil-alt" aria-hidden="true"></i>Оставить заявку от организации</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
