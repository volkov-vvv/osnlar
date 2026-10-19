@extends('layouts.main2')

@push('styles')
    <style>
        .course-page-title {
            color: #094ca1;
            text-align: center;
        }
        .course-page-title .course-study-hours {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin: 0 0 0 0.55rem;
            padding: 0.28rem 0.75rem;
            border: 1px solid #063a7a;
            border-radius: 999px;
            background: linear-gradient(135deg, #094ca1 0%, #0b5cc0 55%, #0a4fa8 100%);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.01em;
            white-space: nowrap;
            vertical-align: middle;
            position: relative;
            top: -0.12em;
            box-shadow: 0 4px 12px rgba(9, 76, 161, 0.28);
        }
        .course-page-title .course-study-hours i {
            font-size: 0.85em;
            color: #fff;
            opacity: 0.95;
        }
        .course-facts {
            margin: 1.75rem 0;
        }
        .course-facts > [class*="col-"] {
            margin-bottom: 1rem;
        }
        @media (min-width: 768px) {
            .course-facts > [class*="col-"] {
                margin-bottom: 0;
            }
        }
        .course-facts .course-start {
            height: 100%;
            margin: 0;
        }
        .course-start {
            display: flex;
            align-items: center;
            margin: 1.75rem 0;
            padding: 1.1rem 1.35rem;
            border: 1px solid #d7e4f5;
            border-radius: 1rem;
            background: linear-gradient(135deg, #eef4fb 0%, #f8fbff 60%, #ffffff 100%);
        }
        .course-start-icon {
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
        .course-start-label {
            margin-bottom: 0.15rem;
            color: #5c6b7a;
            font-size: 0.95rem;
            line-height: 1.3;
        }
        .course-start-date {
            color: #094ca1;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.25;
        }
        a.course-start {
            color: inherit;
            text-decoration: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        a.course-start:hover,
        a.course-start:focus {
            color: inherit;
            text-decoration: none;
            border-color: #094ca1;
            box-shadow: 0 6px 18px rgba(9, 76, 161, 0.1);
        }
        .course-plan {
            background: linear-gradient(135deg, #fff3e8 0%, #fff9f4 60%, #ffffff 100%);
        }
        .course-experts {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.65rem 1.15rem;
            color: grey;
        }
        .course-experts-label {
            margin-right: 0.15rem;
        }
        .course-expert {
            display: inline-flex;
            align-items: center;
            color: #094ca1;
        }
        a.course-expert:hover,
        a.course-expert:focus {
            color: #06357a;
            text-decoration: none;
        }
        .course-expert-avatar {
            width: 36px;
            height: 36px;
            margin-right: 0.5rem;
            border-radius: 50%;
            object-fit: cover;
            background: #eef4fb;
        }
        .course-expert-avatar--empty {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #094ca1;
            font-size: 0.85rem;
        }
        .course-apply {
            padding: 0.5rem 0 11rem;
        }
        .course-apply-card {
            padding: 2.25rem 1.5rem;
            text-align: center;
            border: 1px solid #c5e6ce;
            border-radius: 1rem;
            background: linear-gradient(135deg, #e5f6ea 0%, #f3fbf5 55%, #ffffff 100%);
        }
        .course-apply-title {
            margin: 0 0 0.85rem;
            color: #1b7a3a;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .course-apply-price {
            margin: 0 0 1.25rem;
            color: #3a3a3a;
            font-size: 1.05rem;
        }
        .course-apply-btn {
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
        .course-apply-btn i {
            margin-right: 0.6rem;
        }
        .course-apply-btn:hover,
        .course-apply-btn:focus {
            background: #176b33;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 8px 16px rgba(23, 107, 51, 0.28);
        }
        @media (max-width: 575.98px) {
            .course-apply-title {
                font-size: 1.4rem;
            }
            .course-apply-btn {
                width: 100%;
                max-width: 20rem;
            }
        }
        .course-extra {
            padding-top: 1.5rem;
        }
        .course-extra-title {
            margin: 0 0 0.75rem;
            text-align: center;
            color: #094ca1;
            font-size: 1.45rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .course-extra-lead {
            margin: 0 auto 2rem;
            max-width: 42rem;
            text-align: center;
            color: #1a1a1a;
            font-size: 1.125rem;
            line-height: 1.45;
        }
        @media (min-width: 768px) {
            .course-extra-break {
                display: block;
            }
        }
        .course-extra-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.75rem;
        }
        .course-extra-icon {
            flex: 0 0 4rem;
            width: 4rem;
            height: 4rem;
            margin-right: 1rem;
            border-radius: 50%;
            background: #eef4fb;
            color: #094ca1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }
        .course-extra-item h3 {
            margin: 0 0 0.35rem;
            color: #094ca1;
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .course-extra-item p {
            margin: 0;
            color: #2c2c2c;
            font-size: 1rem;
            line-height: 1.5;
        }
        .course-needs {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 0 0 1rem;
        }
        .course-need {
            display: flex;
            align-items: center;
            flex: 1 1 11rem;
            max-width: 16rem;
            margin: 0 0 1.25rem;
            color: #094ca1;
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .course-need .course-extra-icon {
            flex-basis: 4.5rem;
            width: 4.5rem;
            height: 4.5rem;
            font-size: 1.5rem;
        }
        @media (max-width: 575.98px) {
            .course-extra-title {
                font-size: 1.2rem;
            }
            .course-extra-lead {
                font-size: 1rem;
            }
            .course-need {
                flex: 1 1 100%;
                max-width: none;
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')

    <main class="blog-post">
        <div class="container">
            <div class="d-none d-lg-block">
                <h2 class="course-page-title text-center pt-5 pb-2" data-aos="fade-up">{{$course->title}}@if($course->studyHoursLabel()) <span class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</span>@endif</h2>
            </div>
            <div class="d-lg-none pb-2">
                <h3 class="course-page-title text-center pt-4" data-aos="fade-up">{{$course->title}}@if($course->studyHoursLabel()) <span class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</span>@endif</h3>
            </div>

            <p class="course-experts pb-lg-3 text-center" data-aos="fade-up" data-aos-delay="200">
                <span class="course-experts-label">Эксперты курса:</span>
                @foreach($course->authors as $author)
                    @if($author->is_published)
                        <a class="course-expert" href="{{ route('author.show', $author) }}">
                    @else
                        <span class="course-expert">
                    @endif
                        @if($author->photo)
                            <img class="course-expert-avatar" src="{{ url('storage/' . $author->photo) }}" alt="">
                        @else
                            <span class="course-expert-avatar course-expert-avatar--empty" aria-hidden="true"><i class="fas fa-user"></i></span>
                        @endif
                        {{ $author->display_name }}
                    @if($author->is_published)
                        </a>
                    @else
                        </span>
                    @endif
                @endforeach
            </p>
            <section class="blog-post-featured-img mb-1" data-aos="fade-up" data-aos-delay="300">
                <div class="row">
                    <div class="col">
                        <div class="d-none d-lg-block"><p class="text-center"><img src="{{url('storage/' . $course->image) }}" alt="featured image" class="w-75"></p></div>
                        <div class="d-lg-none pb-2"><p class="text-center"><img src="{{url('storage/' . $course->image) }}" alt="featured image" class="w-100"></p></div>
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    @if(isset($course->price) && $course->price != 0)--}}
{{--                        <div class="col-12 pt-5 text-center course-price">--}}
{{--                            Стомость обучения {{number_format($course->price, 0, ',', ' ')}} руб.--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                <div class="col-12 pt-5 text-center">--}}
{{--                    <a href="--}}
{{--                        @if(isset($course->price) && $course->price != 0)--}}
{{--                            {{route('order.create', $course->id)}}--}}
{{--                        @else--}}
{{--                            {{route('lid.create', ['selectedCourse' => $course->id])}}--}}
{{--                        @endif--}}
{{--                        " class="btn btn-primary btn-lg"><i class="fas fa-arrow-right mr-2"></i>--}}
{{--                        Записаться</a>--}}
{{--                </div>--}}
{{--                </div>--}}

            </section>
            <section class="blog-post-featured-img" data-aos="fade-up" data-aos-delay="300">

                @php
                    $courseFactCount = (int) (bool) $course->starts_at
                        + (int) (bool) $course->studyFormLabel()
                        + (int) (bool) $course->studyWeeksLabel();
                    $courseFactCol = $courseFactCount >= 3 ? 'col-md-4' : ($courseFactCount === 2 ? 'col-md-6' : 'col-12');
                @endphp
                @if($courseFactCount)
                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            <div class="row course-facts">
                                @if($course->studyFormLabel())
                                    <div class="{{ $courseFactCol }}">
                                        <div class="course-start" data-aos="fade-up">
                                            <div class="course-start-icon" aria-hidden="true"><i class="fas fa-graduation-cap"></i></div>
                                            <div>
                                                <div class="course-start-label">Форма обучения</div>
                                                <div class="course-start-date">{{ $course->studyFormLabel() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($course->starts_at)
                                    <div class="{{ $courseFactCol }}">
                                        <div class="course-start" data-aos="fade-up">
                                            <div class="course-start-icon" aria-hidden="true"><i class="fas fa-calendar-alt"></i></div>
                                            <div>
                                                <div class="course-start-label">Старт обучения</div>
                                                <div class="course-start-date">{{ $course->starts_at->locale('ru')->translatedFormat('j F Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($course->studyWeeksLabel())
                                    <div class="{{ $courseFactCol }}">
                                        <div class="course-start" data-aos="fade-up">
                                            <div class="course-start-icon" aria-hidden="true"><i class="fas fa-clock"></i></div>
                                            <div>
                                                <div class="course-start-label">Сроки обучения</div>
                                                <div class="course-start-date">{{ $course->studyWeeksLabel() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-9 mx-auto">
                        <p>{!! $course->description !!}</p>
                    </div>
                </div>
                @if($course->utp)
                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            <a class="course-start course-plan" href="{{ url('storage/' . $course->utp) }}" target="_blank" rel="noopener" data-aos="fade-up">
                                <div class="course-start-icon" aria-hidden="true"><i class="fas fa-file-alt"></i></div>
                                <div>
                                    <div class="course-start-label">Внимательно ознакомьтесь с планом курса</div>
                                    <div class="course-start-date">Учебно-тематический план</div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="row pb-5">

                    <div class="col-lg-9 mx-auto pb-3">
                        <section class="course-extra" data-aos="fade-up">
                            <h2 class="course-extra-title">Как проходят занятия</h2>
                            <p class="course-extra-lead">Все материалы, открытые в ходе курса, доступны для <span class="course-extra-break">изучения до конца года</span></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="course-extra-item">
                                        <div class="course-extra-icon" aria-hidden="true"><i class="fas fa-chalkboard-teacher"></i></div>
                                        <div>
                                            <h3>Онлайн-занятия (лекции и практика)</h3>
                                            <p>2 раза в неделю по 2 академических часа. Записи всех онлайн-занятий сохраняются в системе для последующего просмотра.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="course-extra-item">
                                        <div class="course-extra-icon" aria-hidden="true"><i class="fas fa-book-reader"></i></div>
                                        <div>
                                            <h3>Оффлайн-занятия (лекции и практика)</h3>
                                            <p>В промежутках между онлайн-занятиями слушатель изучает лекционные материалы в записи и выполняет тесты и задания. Преподаватель курса проверяет задания и дает обратную связь.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="course-extra-item">
                                        <div class="course-extra-icon" aria-hidden="true"><i class="fas fa-book-open"></i></div>
                                        <div>
                                            <h3>Самостоятельная работа</h3>
                                            <p>Отрабатывайте полученные навыки. Расширяйте свою экспертизу, изучая литературу из библиотеки курса, собранной специально для слушателей.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="course-extra-item">
                                        <div class="course-extra-icon" aria-hidden="true"><i class="fas fa-file-alt"></i></div>
                                        <div>
                                            <h3>Итоговая аттестационная работа</h3>
                                            <p>Итоговый проект основывается на заданиях, выполненных в ходе курса. Лишь после успешной защиты проекта обучение считается завершенным.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-9 mx-auto">
                        <section class="course-extra" data-aos="fade-up">
                            <h2 class="course-extra-title">Как подготовиться</h2>
                            <p class="course-extra-lead">Для участия в лекциях и практических занятиях вам пригодятся:</p>
                            <div class="course-needs">
                                <div class="course-need">
                                    <div class="course-extra-icon" aria-hidden="true"><i class="fas fa-laptop"></i></div>
                                    <span>Компьютер и стабильный интернет</span>
                                </div>
                                <div class="course-need">
                                    <div class="course-extra-icon" aria-hidden="true"><i class="fas fa-video"></i></div>
                                    <span>Веб-камера</span>
                                </div>
                                <div class="course-need">
                                    <div class="course-extra-icon" aria-hidden="true"><i class="fas fa-microphone"></i></div>
                                    <span>Микрофон</span>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>


        </div>
        @if(!$course->bioeconomy)
        <section class="course-apply">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 mx-auto">
                        <div class="course-apply-card" data-aos="fade-up">
                            <h2 class="course-apply-title">Подать заявку!</h2>
                            @if(isset($course->price) && $course->price != 0)
                                <p class="course-apply-price">Стоимость обучения {{ number_format($course->price, 0, ',', ' ') }} руб.</p>
                            @endif
                            <a href="@if(isset($course->price) && $course->price != 0){{ route('order.create', $course->id) }}@else{{ route('lid.create', ['selectedCourse' => $course->id]) }}@endif" class="course-apply-btn">
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>Записаться
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>

@endsection
