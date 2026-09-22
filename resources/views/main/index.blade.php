@extends('layouts.main2')
@section('content')

<main>
    <section class="edica-header edica-landing-header main-page">
        <div class="container">
            <div class="edica-landing-header-content">
                <div id="edicaLandingHeaderCarousel" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-md-6 carousel-content-wrapper top-banner">
                                    <h1>ITTTT<span style="font-family: Soyuz Grotesk Bold">-компания,</span></h1>
                                    <p>которая специализируется на:</p>
                                    <ul>
                                        <li>создании цифрового образовательного контента</li>
                                        <li>внедрении образовательной платформы &laquo;Основание&raquo;</li>
                                        <li>обучении по различным IT-направлениям</li>
                                    </ul>

                                    <div class="carousel-content-btns">
                                        <a href="{{route('course.index')}}" class="button-main">Выберите курс</a>
                                    </div>
                                    <div class="achievement">
                                        <div><span class="achievement-bold">11 535</span><br>слушателей</div>
                                        <div><span class="achievement-bold">60+</span><br>программ</div>
                                        <div><span class="achievement-bold">85%</span><br>трудоустроились</div>
                                    </div>
                                </div>
                                <div class="col-md-6 carousel-img-wrapper top-banner">
                                    <img src="{{asset('assets/images/people.png')}}" alt="carousel-img" class="img-fluid"
                                         width="650px">
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </section>



    <section class="edica-landing-section about-osnovanie">
        <div class="container">
            <h4 class="edica-landing-section-subtitle-alt">Проекты</h4>
            <div class="row">
                <div class="col-md-6 img-wrapper" data-aos="fade-up-right">
                    <img src="{{asset('assets/images/Slider_1.png')}}" alt="carousel-img" class="img-fluid"
                         width="350px">
                </div>
                <div class="col-md-6" data-aos="fade-up-left">
                    <h2>Федеральный проект «Активные меры содействия занятости»</h2>
                    <p></p>
                    <p>Учебный центр «Основание» открывает предварительную запись на <em>бесплатное</em> обучение по программам
                        дополнительного профессионального образования отдельных категорий граждан в рамках
                        реализации Федерального проекта «Активные меры содействия занятости» <a href="https://xn--80aapampemcchfmo7a3c9ehj.xn--p1ai/new-projects/kadry/" target="_blank">Национального проекта «Кадры»</a>.</p>

                    <div class="carousel-content-btns">
                        <a href="{{route('course.index')}}" class="button-main">Оставить заявку</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="edica-landing-section edica-landing-blog">
        <div class="container">
            <h4 class="edica-landing-section-subtitle-alt">Наши курсы</h4>
            <h2 class="edica-landing-section-title" data-aos="fade-up">Бесплатные курсы, в рамках Федерального проекта</h2>
            <div class="row">
                @foreach($randomCourses as $course)
                    <div class="col-md-4 pt-0 pb-5 landing-blog-post" data-aos="fade-right">
                        <a href="{{route('course.show', $course->id)}}"><img src="{{url('storage/' . $course->prev_img) }}" alt="blog post" class="blog-post-thumbnail"></a>
                        <p class="blog-post-category"></p>
                        <h4 class="blog-post-title">{{$course->title}}</h4>
                        <a href="{{route('course.show', $course->id)}}" class="blog-post-link">Перейти</a>
                    </div>
                @endforeach
            </div>

            <div class="row mt-5">

                <div class="carousel-content-btns mx-auto text-center">
                    <a href="{{route('course.index')}}" type="button" class="btn btn-primary btn-lg px-5"> Все курсы </a>
{{--                    <a href="{{route('course.index')}}" class="button-main ">Все курсы</a>--}}
                </div>
            </div>
        </div>
    </section>

    <section class="edica-landing-section edica-landing-about">
        <div class="container">
            <div class="row">
                <div class="col-md-6" data-aos="fade-up-right">
                    <h4 class="edica-landing-section-subtitle-alt">О нас</h4>
                    <h2 class="edica-landing-section-title">Успешно обучили более 11 000 слушателей</h2>
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
            <h4 class="edica-landing-section-subtitle-alt">Отзывы</h4>

            <header class="edica-header edica-landing-header">
                <div class="container">
                    <div class="edica-landing-header-content">
                        <div id="edicaLandingHeaderCarousel" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#edicaLandingHeaderCarousel" data-slide-to="0" class="">.01</li>
                                <li data-target="#edicaLandingHeaderCarousel" data-slide-to="1" class="">.02</li>
                                <li data-target="#edicaLandingHeaderCarousel" data-slide-to="2" class="">.03</li>
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item">
                                    <div class="row">
                                        <div class="col-md-6 carousel-content-wrapper">
                                            <h1>Вебер Юлия</h1>
                                            <p>41 года, г. Москва</p>
                                            <p>Курс «Азбука Цифры. Киберпсихология - психология личности в виртуальном мире»</p>
                                            <div class="carousel-content-btns">
                                                <p>«Все очень понравилось. Конечно темп приличный, учитывая, что проходила с маленьким ребёнком 8мес. Но все очень продуманно и реально.
                                                    Из всех пройденных за последнее время курсов (а их было немало), очень понравилась организация, четкость, структура, подход, глубина знаний, которую дали и показали.
                                                    Хотелось бы даже продолжения, но курс закончился 👍
                                                    Огромная благодарность всему составу!»
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 carousel-img-wrapper">
                                            <img src="{{asset('assets/images/Veber.Y.png')}}" alt="carousel-img" class="img-fluid" width="350px">
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="row">
                                        <div class="col-md-6 carousel-content-wrapper">
                                            <h1>Мирзоян Элина</h1>
                                            <p>32 года, г. Санкт-Петербург</p>
                                            <p>Курс «Азбука цифры. Нейросети в SMM-маркетинге»</p>
                                            <div class="carousel-content-btns">
                                                <p>«Ваше обучение, это лучшее обучение, которое было в моей жизни. Столько , действительно, полезного и уникального материала, невозможно было даже представить. Обучение превзошло ожидания в сотни раз. Потрясающая команда, и в частности Прохорова Полина, я даже вспомнить не могу, когда в последний раз мое внимание было настолько задержано! Я желаю всем вашим проектам огромного развития и большого количества студентов,  Вы на , определенно, правильном пути!»

                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 carousel-img-wrapper">
                                            <img src="{{asset('assets/images/Mirzoyan.E.png')}}" alt="carousel-img" class="img-fluid" width="350px">
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-md-6 carousel-content-wrapper">
                                            <h1>Агеев Александр</h1>
                                            <p>40 лет, Владимирская обл.</p>
                                            <p>Курс «Азбука цифры. Профессия Python-программист»</p>
                                            <div class="carousel-content-btns">
                                                <p>«Очень понравилась организация обучения. Могу сказать в сравнении. Моя супруга проходила подобное обучение в другой организации. Это кардинально другой уровень. Я благодарю вас от всего сердца за вовлеченность в процесс преподавания и желания помочь понять дисциплину. Откровенно говоря, чувствую грусть, что обучение закончилось.»
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 carousel-img-wrapper">
                                            <img src="{{asset('assets/images/Ageev.a.png')}}" alt="carousel-img" class="img-fluid" width="350px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

        </div>
    </section>

    <section class="edica-landing-section edica-landing-clients">
        <div class="container">
            <h4 class="edica-landing-section-subtitle-alt">Наши партнеры</h4>
            <div class="partners-wrapper">
                <img src="{{asset('assets/images/irpo-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="250">
                <img src="{{asset('assets/images/ranx-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="500">
                <img src="{{asset('assets/images/tgu-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="750">
                <img src="{{asset('assets/images/vnii-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="1000">
                <img src="{{asset('assets/images/rr-logo.png')}}" alt="client logo" data-aos="flip-right" data-aos-delay="1250">
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
                        <img src="{{ asset('assets/images/Albina.jpg')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Каримова Альбина</h6>
                            <p>Руководитель УМЦ</p>
                        </div>
                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="1">
                        <img src="{{ asset('assets/images/AK.jpg')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Корякина Анна</h6>
                            <p>Преподаватель</p>
                        </div>
                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="2" class="active">
                        <img src="{{ asset('assets/images/AI.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Скуратов Александр</h6>
                            <p>Генеральный директор</p>
                        </div>

                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="3">
                        <img src="{{ asset('assets/images/SON.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Давтян Соня</h6>
                            <p>Методист по юридической работе</p>
                        </div>
                    </li>
                    <li data-target="#edicaLandingTestimonialCarousel" data-slide-to="4">
                        <img src="{{ asset('assets/images/VVV.png')}}" alt="avatar">
                        <div class="user-details">
                            <h6>Волков Вячеслав</h6>
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
                <div class="col-md-6 landing-service-card" data-aos="fade-left">
                    <img src="{{asset('assets/images/internet2.png')}}" alt="card img" class="img-fluid service-card-img">
                    <h4 class="service-card-title">Очное и дистанционное обучение</h4>
                    <p class="service-card-description">Наше обучение проходит и в очном и дистанционном формате на платформе LMS, что позволяет нашим слушателям обучаться эффективно с учетом их персонального графика и личного куратора</p>
                </div>
                <div class="col-md-6 landing-service-card" data-aos="fade-right">
                    <img src="{{asset('assets/images/picture.svg')}}" alt="card img" class="img-fluid service-card-img">
                    <h4 class="service-card-title">Видеоуроки в режиме реального времени</h4>
                    <p class="service-card-description">Вы можете активно участвовать в обсуждениях, задавать вопросы и обмениваться мнениями с преподавателем и другими слушателями. Это позволяет не только получить дополнительные объяснения, но и расширить свои знания, услышав разные точки зрения</p>
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
