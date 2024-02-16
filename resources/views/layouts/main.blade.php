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
            <a class="navbar-brand" href="#"><img src="{{asset('assets/images/logo-main.png')}}" width="220"  alt="Основание"></a>
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
                        <a class="nav-link" href="#">Контакты</a>
                    </li>
                </ul>
                <ul class="navbar-nav mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Войти</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="edica-landing-header-content">
            <div id="edicaLandingHeaderCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#edicaLandingHeaderCarousel" data-slide-to="0" class="active">.01</li>
                    <li data-target="#edicaLandingHeaderCarousel" data-slide-to="1">.02</li>
                    <li data-target="#edicaLandingHeaderCarousel" data-slide-to="2">.03</li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-md-6 carousel-content-wrapper">
                                <h1 >Федеральный проект «Содействие занятости»</h1>
                                <p>Учебный центр «Основание» организует <em>бесплатное</em> обучение по программам дополнительного профессионального образования отдельных категорий граждан в рамках реализации федерального <a href="https://trudvsem.ru/information-pages/support-employment/">проекта «Содействие занятости»</a> национального проекта «Демография».</p>
                                <div class="carousel-content-btns">
                                    <a href="#!" class="btn btn-success"><i class="fab fa-apple mr-2"></i> Записаться</a>
                                </div>
                            </div>
                            <div class="col-md-6 carousel-img-wrapper">
                                <img src="{{asset('assets/images/Slider_1.png')}}" alt="carousel-img" class="img-fluid" width="350px">
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row">
                            <div class="col-md-6 carousel-content-wrapper">
                                <h1 >Федеральный проект «Содействие занятости»</h1>
                                <p>Учебный центр «Основание» организует <em>бесплатное</em> обучение по программам дополнительного профессионального образования отдельных категорий граждан в рамках реализации федерального <a href="https://trudvsem.ru/information-pages/support-employment/">проекта «Содействие занятости»</a> национального проекта «Демография».</p>
                                <div class="carousel-content-btns">
                                    <a href="#!" class="btn btn-success"><i class="fab fa-apple mr-2"></i> Записаться</a>
                                </div>
                            </div>
                            <div class="col-md-6 carousel-img-wrapper">
                                <img src="{{asset('assets/images/Slider_1.png')}}" alt="carousel-img" class="img-fluid" width="350px">
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
            <h1 class="banner-title">Подать заявку!</h1>
            <div class="banner-btns-wrapper">
                <button class="btn btn-success">Перейти</button>
            </div>
            <p class="banner-text">Мы поможем Вам быстро и без труда <br> поучаствовать в одном из наших проектов!</p>
        </div>
    </div>
</section>
<footer class="edica-footer" data-aos="fade-up">
    <div class="container">
        <div class="row footer-widget-area">
            <div class="col-md-3">
                <a href="index.html" class="footer-brand-wrapper">
                    <img src="assets/images/logo.svg" alt="edica logo">
                </a>
                <p class="contact-details">hello@edica.com</p>
                <p class="contact-details">+23 3000 000 00</p>
                <nav class="footer-social-links">
                    <a href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a href="#!"><i class="fab fa-twitter"></i></a>
                    <a href="#!"><i class="fab fa-behance"></i></a>
                    <a href="#!"><i class="fab fa-dribbble"></i></a>
                </nav>
            </div>
            <div class="col-md-3">
                <nav class="footer-nav">
                    <a href="#!" class="nav-link">Company</a>
                    <a href="#!" class="nav-link">Android App</a>
                    <a href="#!" class="nav-link">ios App</a>
                    <a href="#!" class="nav-link">Blog</a>
                    <a href="#!" class="nav-link">Partners</a>
                    <a href="#!" class="nav-link">Careers</a>
                </nav>
            </div>
            <div class="col-md-3">
                <nav class="footer-nav">
                    <a href="#!" class="nav-link">FAQ</a>
                    <a href="#!" class="nav-link">Reporting</a>
                    <a href="#!" class="nav-link">Block Storage</a>
                    <a href="#!" class="nav-link">Tools & Integrations</a>
                    <a href="#!" class="nav-link">API</a>
                    <a href="#!" class="nav-link">Pricing</a>
                </nav>
            </div>
            <div class="col-md-3">
                <div class="dropdown footer-country-dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="footerCountryDropdown" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <span class="flag-icon flag-icon-gb flag-icon-squared"></span> United Kingdom <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="footerCountryDropdown">
                        <button class="dropdown-item" href="#">
                            <span class="flag-icon flag-icon-us flag-icon-squared"></span> United States
                        </button>
                        <button class="dropdown-item" href="#">
                            <span class="flag-icon flag-icon-au flag-icon-squared"></span> Australia
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-content">
            <nav class="nav footer-bottom-nav">
                <a href="#!">Privacy & Policy</a>
                <a href="#!">Terms</a>
                <a href="#!">Site Map</a>
            </nav>
            <p class="mb-0">© Edica. 2020 <a href="https://www.bootstrapdash.com" target="_blank" rel="noopener noreferrer" class="text-reset">bootstrapdash</a> . All rights reserved.</p>
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
