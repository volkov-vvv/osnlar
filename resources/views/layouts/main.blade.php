<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Центр повышения квалификации и профессиональной подготовки Основание. Главная страница, основной сайт."/>
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('assets/images/fav.ico')}}">

    <title>Основание :: Главная</title>

    <link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/aos/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}?v3">
    <script src="{{asset('assets/vendors/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/loader.js')}}"></script>

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
<body>
<div class="edica-loader"></div>
<header class="edica-header edica-landing-header main-page">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{route('main.index')}}"><img src="{{asset('assets/images/logo-main.png')}}"
                                                                        width="260" alt="Основание"></a>
            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#edicaMainNav"
                    aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="edicaMainNav">
                <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('main.index')}}">Главная <span
                                class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('about.index')}}">О нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('services.index')}}">Услуги</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Курсы</a>
                        <div class="dropdown-menu" aria-labelledby="blogDropdown">
                            <a class="dropdown-item" href="{{route('course.index')}}">Бесплатные курсы</a>
                            <a class="dropdown-item" href="{{route('commerce.index')}}">Платные курсы</a>
                            <a class="dropdown-item" href="{{route('archive.index')}}">Архив</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://lms.osnovanie.info/login/index.php">Платформа</a>
                    </li>
                </ul>

                @if( isset(auth()->user()->id) )
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">{{auth()->user()->name}}</a>
                            <div class="dropdown-menu" aria-labelledby="blogDropdown">
                                <!--
                                <a class="dropdown-item btn btn-link" href="#">
                                    Настройки
                                </a>
                                -->
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
                                <a class="dropdown-item" href="#">
                                    <form action="{{route('logout')}}" method="post">
                                        @csrf
                                        <input class="btn btn-link" type="submit" value="Выйти">
                                    </form>
                                </a>
                            </div>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="button-login" href="{{route('login')}}">Войти</a>
                        </li>
                    </ul>
                @endif

            </div>
        </nav>
        <div class="edica-landing-header-content">
            <div id="edicaLandingHeaderCarousel" class="carousel slide" data-ride="carousel">

                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-md-6 carousel-content-wrapper top-banner">
                                <h1>IT<span style="font-family: Soyuz Grotesk Bold">-компания,</span></h1>
                                <p>которая специализируется на:</p>
                                <ul>
                                    <li>создании цифрового образовательного контента</li>
                                    <li>разработке образовательной платформы &laquo;Основание&raquo;</li>
                                    <li>обучении по различным IT-направлениям</li>
                                </ul>

                                <div class="carousel-content-btns">
                                    <a href="{{route('commerce.index')}}" class="button-main">Выберите курс</a>
                                </div>
                                <div class="achievement">
                                    <div><span class="achievement-bold">8000+</span><br>слушателей</div>
                                    <div><span class="achievement-bold">40+</span><br>программ</div>
                                    <div><span class="achievement-bold">82%</span><br>трудоустроились</div>
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
</header>

@yield('content')


<section class="edica-footer-banner-section">
    <div class="container">
        <div class="footer-banner" data-aos="fade-up">
            <h1 class="banner-title">Подать заявку на 2025 год!</h1>
            <div class="banner-btns-wrapper">
                <div class="carousel-content-btns">
                    <a href="{{route('lid.create')}}" class="btn btn-primary"><i class="fas fa-arrow-right mr-2"></i>
                        Записаться</a>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="edica-footer" data-aos="fade-up">
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
{{-- @include('cookieConsent::index') --}}
</body>
</html>
