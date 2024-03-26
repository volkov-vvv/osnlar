@extends('layouts.main')
@section('content')

<main>
    <section class="edica-landing-section edica-landing-about">
        <div class="container">
            <div class="row">
                <div class="col-md-6" data-aos="fade-up-right">
                    <h4 class="edica-landing-section-subtitle-alt">О нас</h4>
                    <h2 class="edica-landing-section-title">Успешно обучили более 8000+ слушателей</h2>
                    <p>Мы современная образовательная организация, стремимся делать наши курсы максимально удобными и эффективными. Основные принципы, которых мы придерживаемся:</p>
                    <ul class="landing-about-list">
                        <li>Привлечение лучших специалистов</li>
                        <li>Построение индивидуальной образовательной траектории</li>
                        <li>Создание не только курса, но и экосистемы педагогической помощи и технической поддержки</li>
                    </ul>
                </div>
                <div class="col-md-6" data-aos="fade-up-left">
                    <img src="{{asset('assets/images/bilding.png')}}" alt="about" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <section class="edica-landing-section edica-landing-clients">
        <div class="container">
            <h4 class="edica-landing-section-subtitle-alt">Наши партнеры</h4>
            <div class="partners-wrapper">
                <img src="{{asset('assets/images/irpo-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="250">
                <img src="{{asset('assets/images/ranx-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="500">
                <img src="{{asset('assets/images/tgu-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="750">
                <img src="{{asset('assets/images/rr-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="1000">
            </div>
        </div>
    </section>

    <section class="edica-landing-section edica-landing-blog">
        <div class="container">
            <h4 class="edica-landing-section-subtitle" data-aos="fade-up">Наши курсы</h4>
            <h2 class="edica-landing-section-title" data-aos="fade-up">Выберите понравившийся курс</h2>
            <div class="row">
                @foreach($randomCourses as $course)
                <div class="col-md-4 landing-blog-post" data-aos="fade-right">
                    <img src="{{url('storage/' . $course->prev_img) }}" alt="blog post" class="blog-post-thumbnail">
                    <p class="blog-post-category"></p>
                    <h4 class="blog-post-title">{{$course->title}}</h4>
                    <a href="{{route('course.show', $course->id)}}" class="blog-post-link">Перейти</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="edica-landing-section edica-landing-testimonials" data-aos="fade-up">
        <div class="container">
            <div id="edicaLandingTestimonialCarousel" class="carousel slide landing-testimonial-carousel" data-ride="carousel">
                <div class="text-center py-4">
                    <img src="{{asset('assets/images/quote.svg')}}" alt="quote">
                </div>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item">
                        <blockquote class="testimonial">
                            <p>“Открой свой потенциал с нами - образование, которое вдохновляет!” </p>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="testimonial">
                            <p>“Мы предлагаем студентам не просто обучение, а целостный опыт, включающий в себя интерактивные уроки, практические занятия и менторскую поддержку.” </p>
                        </blockquote>
                    </div>
                    <div class="carousel-item active">
                        <blockquote class="testimonial">
                            <p>“Наша образовательная организация - это место, где знания преображаются в возможности, а учебные пути ведут к успеху. Мы предлагаем индивидуальный подход к каждому студенту, высококвалифицированных преподавателей и современные образовательные программы, способствующие широкому и глубокому пониманию предметов.” </p>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="testimonial">
                            <p>“Мы гарантируем вам высокое качество знаний, практические навыки и поддержку на каждом этапе вашего образовательного пути. И не забывайте, что образование - это инвестиция в ваше будущее.” </p>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="testimonial">
                            <p>“Образование - ключ к вашему будущему успеху!” </p>
                        </blockquote>
                    </div>
                </div>
                <ol class="carousel-indicators">
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="0">
                        <img src="{{ asset('assets/images/LN.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Людмила Никитченко</h6>
                            <p>Руководитель УМЦ</p>
                        </div>
                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="1">
                        <img src="{{ asset('assets/images/PAV.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Дмитрий Павлов</h6>
                            <p>Преподаватель</p>
                        </div>
                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="2" class="active">
                        <img src="{{ asset('assets/images/AI.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Скуратов Александр</h6>
                            <p>Директор</p>
                        </div>

                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="3">
                        <img src="{{ asset('assets/images/SON.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Соня </h6>
                            <p>Методист</p>
                        </div>
                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="4">
                        <img src="{{ asset('assets/images/VVV.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Вячеслав Волков</h6>
                            <p>Академический директор</p>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </section>
    <section class="edica-landing-section edica-landing-services">
        <div class="container">
            <h4 class="edica-landing-section-subtitle">Наши особенности</h4>
            <h2 class="edica-landing-section-title">Почему выбирают нас:</h2>
            <div class="row">
                <div class="col-md-6 landing-service-card" data-aos="fade-right">
                    <img src="{{asset('assets/images/picture.svg')}}" alt="card img" class="img-fluid service-card-img">
                    <h4 class="service-card-title">Видеоуроки в режиме реального времени</h4>
                    <p class="service-card-description">Обучение проходит в режиме реального времени, что способствует более глубокому пониманию материала. Вы можете активно участвовать в обсуждениях, задавать вопросы и обмениваться мнениями с преподавателем и другими слушателями. Это позволяет не только получить дополнительные объяснения, но и расширить свои знания, услышав разные точки зрения</p>
                </div>
                <div class="col-md-6 landing-service-card" data-aos="fade-left">
                    <img src="{{asset('assets/images/internet2.png')}}" alt="card img" class="img-fluid service-card-img">
                    <h4 class="service-card-title">Дистанционное обучение</h4>
                    <p class="service-card-description">Наше обучение проходит в дистанционном формате на платформе LMS, что позволяет нашим слушателям обучаться эффективно с учетом их персонального графика и личного куратора</p>
                </div>
                <div class="col-md-6 landing-service-card" data-aos="fade-right">
                    <img src="{{asset('assets/images/goal.svg')}}" alt="card img" class="img-fluid service-card-img">
                    <h4 class="service-card-title">Максимальная персонализация обучения</h4>
                    <p class="service-card-description">Для каждого слушателя наших курсов мы строим индивидуальную образовательную траекторию на основе "умных" алгоритмов</p>
                </div>
                <div class="col-md-6 landing-service-card" data-aos="fade-left">
                    <img src="{{asset('assets/images/chat-bubble.svg')}}" alt="card img" class="img-fluid service-card-img">
                    <h4 class="service-card-title">Поддержка в удобном формате</h4>
                    <p class="service-card-description">Поддержка кураторов 24/7: адаптация слушателей во время обучения, своевременное оповещение о важных моментах на курсе, сбор обратной связи для улучшения качества курса</p>
                </div>
            </div>
        </div>
    </section>


{{--    <section class="edica-landing-section edica-landing-blog-posts">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="blog-post-card blog-post-1 mb-4 mb-md-0" data-aos="fade-right">--}}
{{--                        <p class="post-category">App Design</p>--}}
{{--                        <h2 class="post-title">Check our latest blog post for more update.</h2>--}}
{{--                        <a href="#!" class="post-link">Learn more</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="blog-post-card blog-post-2" data-aos="fade-left">--}}
{{--                        <p class="post-category">App Design</p>--}}
{{--                        <h2 class="post-title">Check our latest blog post for more update.</h2>--}}
{{--                        <a href="#!" class="post-link">Learn more</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
</main>

@endsection
