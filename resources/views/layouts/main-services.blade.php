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
        <title>Основание :: Главная</title>
        <meta property="og:title" content="Основание :: Главная"/>
    @endif

    <link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/aos/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}?v6">
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
<header class="edica-header edica-landing-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{route('main.index')}}"><img src="{{asset('assets/images/logo-main.png')}}" width="220"  alt="Основание"></a>
            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#edicaMainNav" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="edicaMainNav">
                <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('main.index')}}">Главная <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('about.index')}}">О нас</a>
                    </li>
{{--                    <li class="nav-item active">--}}
{{--                        <a class="nav-link" href="{{route('services.index')}}">Услуги</a>--}}
{{--                    </li>--}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Курсы</a>
                        <div class="dropdown-menu" aria-labelledby="blogDropdown">
                            <a class="dropdown-item" href="{{route('future.index')}}">Код будущего</a>
                            <a class="dropdown-item" href="{{route('course.index')}}">Бесплатные курсы</a>
                            <a class="dropdown-item" href="{{route('commerce.index')}}">Платные курсы</a>
                            <a class="dropdown-item" href="{{route('archive.index')}}">Архив</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://lms.osnovanie.info/login/index.php">Платформа</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto p-3">
                    <li class="nav-item">
                        <a class="button-lid-create" role="button" href="{{route('lid.create')}}">Оставить заявку</a>
                    </li>
                </ul>

                @if( isset(auth()->user()->id) )
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" id="blogDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(auth()->user()->avatar)
                                    <img src="{{ url('storage/' . auth()->user()->avatar) }}" alt="" class="rounded-circle me-2" width="28" height="28" style="object-fit: cover;">
                                @endif
                                {{auth()->user()->name}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="blogDropdown">
                                <!--
                                <a class="dropdown-item btn btn-link" href="#">
                                    Настройки
                                </a>
                                -->
                                <a class="dropdown-item btn btn-link" href="
                            @switch(auth()->user()->role)
                            @case(1) {{route('admin.main.index')}}
                            @break
                            @case(2) {{route('agent.main.index')}}
                            @break
                            @case(3) {{route('cc.main.index')}}
                            @break
                            @default
                                    {{route('user.index')}}
                            @endswitch
                            ">
                                    @if(in_array((int) auth()->user()->role, [1, 3], true))
                                        Административная панель
                                    @else
                                        Личный кабинет
                                    @endif
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Профиль</a>
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
                    <ul class="navbar-nav ml-auto p-3">
                        <li class="nav-item">
                            <a class="btn btn-danger" role="button" href="{{route('lid.create')}}">Оставить заявку</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="button-login" href="{{route('login')}}">Войти</a>
                        </li>
                    </ul>
                @endif

            </div>
        </nav>

    </div>
</header>

@yield('content')



@include('includes.footer')

<script src="{{asset('assets/vendors/popper.js/popper.min.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/vendors/aos/aos.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}?v3"></script>
<script>
    AOS.init({
        duration: 1600,
        once: true,
        offset: 80
    });
</script>
</body>
</html>
@include('cookie-consent::index')
