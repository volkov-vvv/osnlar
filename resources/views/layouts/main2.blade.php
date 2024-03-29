<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Основание :: Главная</title>

    <link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/aos/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <script src="{{asset('assets/vendors/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/loader.js')}}"></script>
</head>
<body>
<div class="edica-loader"></div>
<header class="edica-header edica-landing-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{route('main.index')}}"><img src="{{asset('assets/images/logo-main.png')}}" width="220"  alt="Основание"></a>
            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#edicaMainNav" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="edicaMainNav">
                <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('main.index')}}">Главная <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">О нас</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Курсы</a>
                        <div class="dropdown-menu" aria-labelledby="blogDropdown">
                            <a class="dropdown-item" href="{{route('course.index')}}">Актуальные курсы</a>
                            <a class="dropdown-item" href="#">Архив</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://lms.osnovanie.info/login/index.php">Платформа</a>
                    </li>
                </ul>

                @if( isset(auth()->user()->id) )
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{auth()->user()->name}}</a>
                            <div class="dropdown-menu" aria-labelledby="blogDropdown">
                                <a class="dropdown-item btn btn-link" href="#">
                                    Настройки
                                </a>
                                <a class="dropdown-item btn btn-link" href="
                            @switch(auth()->user()->role)
                            @case(1) {{auth()->user()->role == 1 ? route('admin.main.index') : ''}}
                            @break
                            @case(2) {{auth()->user()->role == 3 ? route('cc.main.index') : ''}}
                            @break
                            @default
                                    {{route('cc.main.index')}}
                            @endswitch
                            ">
                                    Административная панель
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
                            <a class="nav-link" href="{{route('login')}}">Войти</a>
                        </li>
                    </ul>
                @endif

            </div>
        </nav>

    </div>
</header>

@yield('content')



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
<footer class="edica-footer" data-aos="fade-up">
    <div class="container">
        <div class="row footer-widget-area">
            <div class="col-md-3">
                <nav class="footer-nav">
                    <p class="contact-details">ОСНОВАНИЕ</p>
                    <p class="contact-details">edu@partnerdpo.ru</p>
                    <p class="contact-details">+7 (499) 609-60-20</p>
                    <nav class="footer-social-links">
                        <a href="{{url('https://vk.com/osnovanie_study')}}" target="_blank"><i class="fab fa-vk"></i></a>
                        <a href="{{url('https://ok.ru/group/70000005562055')}}" target="_blank"><i class="fab fa-odnoklassniki"></i></a>
                        <a href="{{url('https://t.me/osnovanie_study')}}" target="_blank"><i class="fab fa-telegram"></i></a>
                        <a href="#!"><i class="fab fa-yandex" target="_blank"></i></a>
                    </nav>
                </nav>
            </div>
            <div class="col-md-3">
                <nav class="footer-nav">
                    <a href="#!" class="nav-link">Реквизиты</a>
                    <a href="#!" class="nav-link">Android приложение</a>
                    <a href="#!" class="nav-link">ios приложение</a>
                    <a href="#!" class="nav-link">Блог</a>
                    <a href="#!" class="nav-link">Партнеры</a>
                    <a href="#!" class="nav-link">Вакансии</a>
                </nav>
            </div>
            <div class="col-md-3">
                <nav class="footer-nav">
                    <a href="#!" class="nav-link">FAQ</a>
                    <a href="#!" class="nav-link">Цены и услуги</a>
                    <a href="#!" class="nav-link">Условия сотрудничества</a>
                    <a href="#!" class="nav-link">Интеграция</a>
                    <a href="#!" class="nav-link">API</a>
                    <a href="#!" class="nav-link">Комментарии</a>
                </nav>
            </div>
            <div class="col-md-3">
                <div class="dropdown footer-country-dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="footerCountryDropdown" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <span class="flag-icon flag-icon-ru flag-icon-squared"></span> Русский <i class="fas fa-chevron-down ml-2"></i>
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
</body>
</html>
