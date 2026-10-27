<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if(isset($pageDescription))
        <meta name="description" content="{{$pageDescription}}"/>
        <meta property="og:description" content="{{$pageDescription}}"/>
    @endif

    @if(isset($pageTitle))
        <meta property="og:title" content="{{$pageTitle}}"/>
        <title>{{$pageTitle}}</title>
    @else
        <title>Главная</title>
        <meta property="og:title" content="Главная"/>
    @endif

    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('assets/images/fav.ico')}}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/aos/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}?v49">
    <style>
        header.edica-header .navbar-light .navbar-nav .active > .nav-link,
        header.edica-header .navbar-light .navbar-nav .nav-link.active {
            color: #094ca1;
        }
        header.edica-header .bioeconomy-nav-leaf {
            margin-right: 0.35rem;
            color: #1f8a42;
            font-size: 0.85em;
        }
        @media (max-width: 991.98px) {
            header.edica-header .navbar-nav .nav-item.active .nav-link,
            header.edica-header .navbar-nav .nav-link.active {
                color: #094ca1;
                border-color: #d0d5db;
            }
        }
    </style>
    <script src="{{asset('assets/vendors/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/loader.js')}}"></script>
    @stack('styles')

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (m, e, t, r, i, k, a) {
            m[i] = m[i] || function () {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(96624355, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/96624355" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->

    <!-- Top.Mail.Ru counter -->
    <script type="text/javascript">
        var _tmr = window._tmr || (window._tmr = []);
        _tmr.push({id: "3518452", type: "pageView", start: (new Date()).getTime()});
        (function (d, w, id) {
            if (d.getElementById(id)) return;
            var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
            ts.src = "https://top-fwz1.mail.ru/js/code.js";
            var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
            if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
        })(document, window, "tmr-code");
    </script>
    <noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3518452;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
    <!-- /Top.Mail.Ru counter -->


</head>
<body class="d-flex flex-column min-vh-100">
<div class="edica-loader"></div>
{{--box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05), inset 0 -1px 0 rgba(0, 0, 0, 0.15);
sticky-top  bg-white bg-opacity-75
--}}
<header class="edica-header sticky-top bg-opacity-75" style="background-color: rgba(255, 255, 255, 0.7); backdrop-filter: blur(5px);  box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">

            <div class="d-none d-lg-block"> <a class="navbar-brand" href="{{route('main.index')}}"><img src="{{asset('assets/images/logo-main-clear.png')}}" width="220"  alt="Основание"></a></div>
            @php
                $mobileHeaderCta = null;
                if (request()->routeIs('course.show') && isset($course) && empty($course->bioeconomy)) {
                    $mobileHeaderCta = [
                        'label' => 'Записаться',
                        'url' => (isset($course->price) && $course->price != 0)
                            ? route('order.create', $course->id)
                            : route('lid.create', ['selectedCourse' => $course->id]),
                        'class' => 'mobile-header-cta--enroll',
                        'icon' => true,
                    ];
                } elseif (request()->routeIs('bioeconomy.index')) {
                    $mobileHeaderCta = [
                        'label' => 'Оставить заявку',
                        'url' => route('org.bioeconomy.create'),
                        'class' => 'mobile-header-cta--enroll',
                        'icon' => false,
                    ];
                } elseif (request()->routeIs('main.index', 'course.index')) {
                    $mobileHeaderCta = [
                        'label' => 'Оставить заявку',
                        'url' => route('lid.create'),
                        'class' => 'btn btn-danger',
                        'icon' => false,
                    ];
                }
            @endphp
            <div class="d-lg-none mobile-header-bar{{ $mobileHeaderCta ? ' mobile-header-bar--with-cta' : '' }}">
                <a class="navbar-brand mobile-header-logo mb-0" href="{{route('main.index')}}">
                    <img src="{{asset('assets/images/logo-main-little.png')}}" width="40" height="40" alt="Основание">
                </a>
                @if($mobileHeaderCta)
                    <a class="{{ $mobileHeaderCta['class'] }} mobile-header-cta" role="button" href="{{ $mobileHeaderCta['url'] }}">
                        @if(!empty($mobileHeaderCta['icon']))
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        @endif
                        {{ $mobileHeaderCta['label'] }}
                    </a>
                @endif
            </div>

            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#edicaMainNav" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="edicaMainNav">
                <ul class="navbar-nav mt-2 mt-lg-0 edica-main-nav">
{{--                    <li class="nav-item {{ request()->is('/') ? 'active' : null }}">--}}
{{--                        <a class="nav-link" href="{{route('main.index')}}">Главная</a>--}}
{{--                    </li>--}}
                    <li class="nav-item {{ request()->is('about*') ? 'active' : null }}">
                        <a class="nav-link" href="{{route('about.index')}}">О нас</a>
                    </li>
                    {{-- Desktop: dropdown --}}
                    <li class="nav-item dropdown d-none d-lg-block
                    @if(request()->is('course*') || request()->is('future*') || request()->is('commerce*') || request()->is('archive*'))
                        active
                    @endif
                        ">
                        <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Курсы</a>
                        <div class="dropdown-menu" aria-labelledby="blogDropdown">
{{--                            <a class="dropdown-item" href="{{route('future.index')}}">Код будущего</a>--}}
                            <a class="dropdown-item" href="{{route('course.index')}}">- Бесплатные курсы «Активные меры содействия занятости»</a>
                            <a class="dropdown-item" href="{{route('commerce.index')}}">- Софинансирование обучения «Профессии будущего»</a>
                            <a class="dropdown-item" href="{{route('archive.index')}}">- Архив</a>
                        </div>
                    </li>
                    {{-- Mobile: flat list --}}
                    <li class="nav-item nav-item-course d-lg-none {{ request()->is('course*') ? 'active' : null }}">
                        <a class="nav-link" href="{{route('course.index')}}">Бесплатные курсы (АМСЗ)</a>
                    </li>
                    <li class="nav-item nav-item-course d-lg-none {{ request()->is('commerce*') ? 'active' : null }}">
                        <a class="nav-link" href="{{route('commerce.index')}}">Профессии будущего</a>
                    </li>
                    <li class="nav-item {{ request()->is('bioeconomy*', 'org/bioeconomy*') ? 'active' : null }}">
                        <a class="nav-link" href="{{route('bioeconomy.index')}}"><i class="fas fa-leaf bioeconomy-nav-leaf" aria-hidden="true"></i>Биоэкономика</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://lms.osnovanie.info/login/index.php">Платформа</a>
                    </li>

                    @guest
                    <li class="nav-item nav-item-social nav-item-social-first">
                        <a class="nav-link" href="{{url('https://max.ru/id7751117260_biz')}}" target="_blank" rel="noopener noreferrer">
                            <img class="social-icon-max" src="{{asset('assets/images/Max_logo_2025.png')}}" width="25" height="25" alt="Макс">
                            <span class="d-lg-none">Макс</span>
                        </a>
                    </li>
                    <li class="nav-item nav-item-social">
                        <a class="nav-link" href="{{url('https://t.me/osnovanie_study')}}" target="_blank" rel="noopener noreferrer">
                            <img src="{{asset('assets/images/Telegram_Messenger.png')}}" width="28" height="28" alt="Телеграмм">
                            <span class="d-lg-none">Телеграмм</span>
                        </a>
                    </li>
                    @endguest

                    @if( isset(auth()->user()->id) )

                        <li class="nav-item dropdown ml-lg-auto">
                            <a class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" id="blogDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(auth()->user()->avatar)
                                    <img src="{{ url('storage/' . auth()->user()->avatar) }}" alt="" class="rounded-circle mr-2" width="28" height="28" style="object-fit: cover;">
                                @endif
                                {{auth()->user()->name}}
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="blogDropdown">
                                <a class="dropdown-item btn btn-link" href="
                            @switch(auth()->user()->role)
                                @case(1) {{route('admin.main.index')}}">Административная панель
                                    @break
                                    @case(3) {{route('cc.main.index')}}">Административная панель
                                    @break
                                    @default
                                    {{route('user.index')}}">Личный кабинет
                                    @endswitch
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Профиль</a>
                                @if((int) auth()->user()->role === 10)
                                    <a class="dropdown-item" href="{{ route('user.documents') }}">Документы</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('help.index') }}">Справка</a>
                                <form action="{{route('logout')}}" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt mr-2" aria-hidden="true"></i>Выйти
                                    </button>
                                </form>
                            </div>
                        </li>

                    @else

                        <li class="nav-item nav-item-lid-create mr-lg-3 ml-lg-3">
                            <a class="button-lid-create{{ request()->routeIs('bioeconomy.index') ? ' button-lid-create--bio' : '' }}" role="button" href="{{ request()->routeIs('bioeconomy.index') ? route('org.bioeconomy.create') : route('lid.create') }}">Оставить заявку</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-lg-none" href="{{route('login')}}">Войти</a>
                            <a class="button-login d-none d-lg-inline-flex" href="{{route('login')}}">Войти</a>
                        </li>
                    @endif

                </ul>

            </div>
        </nav>

    </div>
</header>

<div class="flex-grow-1 w-100">
@yield('content')
</div>

{{--<section class="edica-footer-banner-section">--}}
{{--    <div class="container">--}}
{{--        <div class="footer-banner" data-aos="fade-up">--}}
{{--            <h1 class="banner-title">Подать заявку!</h1>--}}
{{--            <div class="banner-btns-wrapper">--}}
{{--                <div class="carousel-content-btns">--}}
{{--                    <a href="{{route('lid.create')}}" class="btn btn-primary"><i class="fas fa-arrow-right mr-2"></i> Записаться</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
<footer class="edica-footer mt-auto" data-aos="fade-up">
    <div class="container">
        <div class="row footer-widget-area">
            <div class="col-md-3">
                <nav class="footer-nav">
                    <p class="">ООО «Центр повышения квалификации и профессиональной подготовки «Основание»</p>
                    <p class="">Адрес: 129110, г. Москва, ул. Гиляровского д. 57, стр. 1.</p>
                    <p class="">+7 (499) 609-60-20</p>
                    <p class="">edu@partnerdpo.ru</p>


                    <nav class="footer-social-links">
                        <a href="{{url('https://vk.com/osnovanie_study')}}" target="_blank"><i class="fab fa-vk"></i></a>
                        <a href="{{url('https://ok.ru/group/70000005562055')}}" target="_blank"><i class="fab fa-odnoklassniki"></i></a>
                        <a href="{{url('https://t.me/osnovanie_study')}}" target="_blank"><i class="fab fa-telegram"></i></a>
                        {{--                        <a href="#!"><i class="fab fa-yandex" target="_blank"></i></a>--}}
                    </nav>
                </nav>
            </div>
            <div class="col-md-3">
                <nav class="footer-nav">
                    <p>Реквизиты</p>
                    <p>ИНН/КПП: 7751117260/770201001</p>
                    <p>ОГРН: 5177746204763</p>
                    <p>Режим работы</p>
                    <p>Пн-Пт, 9:00 – 18:00</p>
                </nav>
            </div>
            <div class="col-md-3">
                <nav class="footer-nav">
                    <a href="{{asset('files/reg_org.pdf')}}" class="nav-link" target="_blank">Карточка организации</a>
                    <a href="#!" class="nav-link">Блог</a>
                    <a href="#!" class="nav-link">FAQ</a>
                    {{--                    <a href="#!" class="nav-link">Цены и услуги</a>--}}
                    <a href="#!" class="nav-link">Условия сотрудничества</a>
                    <a href="#!" class="nav-link">Партнеры</a>
                    <a href="{{ route('author.index') }}" class="nav-link">Авторы / Эксперты</a>
                </nav>
            </div>
            <div class="col-md-3">
                <div class="dropdown footer-country-dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="footerCountryDropdown"
                            data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <span class="flag-icon flag-icon-ru flag-icon-squared"></span> Русский <i
                            class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="footerCountryDropdown">
                        <button class="dropdown-item" href="#">
                            <span class="flag-icon flag-icon-gb flag-icon-squared"></span> Английский
                        </button>
                        <button class="dropdown-item" href="#">
                            <span class="flag-icon flag-icon-сhn flag-icon-squared"></span> Китайский
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-content">
            <nav class="nav footer-bottom-nav">
                <a href="{{asset('files/lic.pdf')}}" target="_blank">Образовательная лицензия №Л035-01298-77/00180209 от 29.12.2021</a>
                <a href="#!">Карта сайта</a>
            </nav>
            <p class="mb-0">© Все права защищены</p>
        </div>
    </div>

</footer>

<script src="{{asset('assets/vendors/popper.js/popper.min.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/vendors/aos/aos.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
<script>
    AOS.init({
        duration: 2000
    });
</script>

@stack('scripts')
@include('cookie-consent::index')
</body>
</html>

