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
        .course-tabs.nav-tabs {
            border-bottom: 0;
            gap: 0.75rem;
        }
        .course-tabs.nav-tabs > .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            flex: 1 1 auto;
            margin-bottom: 0;
            padding: 0.9rem 1rem;
            border: 1px solid #e3e6ea;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            color: #6c757d;
            font-weight: 700;
            line-height: 1.3;
            text-align: center;
            white-space: normal;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
        }
        .course-tab-hours {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.7rem;
            border-radius: 999px;
            background: #fff;
            color: #094ca1;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.2;
            box-shadow: 0 2px 8px rgba(9, 76, 161, 0.08);
        }
        .course-tab-hours i {
            margin-right: 0.35rem;
        }
        .course-tabs.nav-tabs > .nav-link:hover,
        .course-tabs.nav-tabs > .nav-link:focus {
            color: #495057;
            background: linear-gradient(135deg, #f3f4f6 0%, #f8f9fa 100%);
            border-color: #d5d8dc;
            outline: 0;
        }
        .course-tabs.nav-tabs > .nav-link.active,
        .course-tabs.nav-tabs > .nav-link.active:hover,
        .course-tabs.nav-tabs > .nav-link.active:focus {
            color: #063a7a;
            background: linear-gradient(135deg, #c5ddf8 0%, #dcecfc 55%, #eef6ff 100%);
            border-color: #094ca1;
            box-shadow: 0 6px 18px rgba(9, 76, 161, 0.1);
            outline: 0;
        }
        @media (max-width: 767.98px) {
            .course-page-title {
                font-size: 1.6rem;
            }
            .course-page-lead {
                font-size: 16px;
            }
            .course-tabs.nav-tabs {
                flex-direction: column;
                align-items: stretch;
            }
            .course-tabs.nav-tabs > .nav-link {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')

    <main class="blog">
        <div class="container">

            <div class="row px-3 px-md-5 py-4 py-md-5" data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center course-page-title">Запись на бесплатное обучение в 2027 году</h1>
                    <p class="text-center course-page-lead">
                        В рамках реализации федерального проекта «Активные меры содействия занятости» проходит предварительная запись на бесплатное обучение в 2027 году. 
                    </p>
                </div>
            </div>

            <nav>
                <div class="nav nav-tabs nav-fill course-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Все</button>
                    <button class="nav-link" id="nav-digital-tab" data-toggle="tab" data-target="#nav-digital" type="button" role="tab" aria-controls="nav-digital" aria-selected="false"><span class="course-tab-title">Профессиональная переподготовка</span><span class="course-tab-hours"><i class="fas fa-clock" aria-hidden="true"></i>256 ч.</span></button>
                    <button class="nav-link" id="nav-designed-tab" data-toggle="tab" data-target="#nav-designed" type="button" role="tab" aria-controls="nav-designed" aria-selected="false"><span class="course-tab-title">Повышение квалификации</span><span class="course-tab-hours"><i class="fas fa-clock" aria-hidden="true"></i>144 ч.</span></button>
                    <button class="nav-link" id="nav-business-tab" data-toggle="tab" data-target="#nav-business" type="button" role="tab" aria-controls="nav-business" aria-selected="false"><span class="course-tab-title">Повышение квалификации</span><span class="course-tab-hours"><i class="fas fa-clock" aria-hidden="true"></i>72 ч.</span></button>
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
                    </section>
                </div>
                <div class="tab-pane fade" id="nav-digital" role="tabpanel" aria-labelledby="nav-digital-tab" tabindex="0">
                    <section class="featured-posts-section">
                        <div class="row">
                            @foreach($coursesS1 as $course)
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
                    </section>
                </div>
                <div class="tab-pane fade" id="nav-designed" role="tabpanel" aria-labelledby="nav-designed-tab" tabindex="0">
                    <section class="featured-posts-section">
                        <div class="row">
                            @foreach($coursesS2 as $course)
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
                    </section>
                </div>
                <div class="tab-pane fade" id="nav-business" role="tabpanel" aria-labelledby="nav-business-tab" tabindex="0">
                    <section class="featured-posts-section">
                        <div class="row">
                            @foreach($coursesS3 as $course)
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
                    </section>
                </div>
            </div>



        </div>

    </main>

@endsection
