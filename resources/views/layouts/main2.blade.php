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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/aos/aos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}?v108">
    <style>
        header.edica-header.sticky-top {
            top: 0;
            z-index: 1030;
        }

        @media (min-width: 992px) {
            header.edica-header.sticky-top {
                padding: 0.85rem 0 0;
                background: transparent;
                box-shadow: none;
            }

            header.edica-header .header-glass {
                position: relative;
                width: 100%;
                max-width: 1140px;
                margin: 0 auto;
                padding: 0 15px;
                isolation: isolate;
                border-radius: 1.25rem;
                border: 1px solid rgba(255, 255, 255, 0.72);
                background:
                    linear-gradient(
                        145deg,
                        rgba(255, 255, 255, 0.82) 0%,
                        rgba(255, 255, 255, 0.55) 42%,
                        rgba(232, 240, 250, 0.72) 100%
                    );
                background-clip: padding-box;
                backdrop-filter: blur(18px) saturate(160%);
                -webkit-backdrop-filter: blur(18px) saturate(160%);
                box-shadow:
                    0 14px 36px rgba(15, 23, 42, 0.1),
                    0 4px 14px rgba(9, 76, 161, 0.06),
                    inset 0 1px 0 rgba(255, 255, 255, 0.95),
                    inset 0 -1px 0 rgba(9, 76, 161, 0.06);
            }

            header.edica-header .header-glass::before {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: inherit;
                padding: 1px;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(147, 197, 253, 0.28) 48%, rgba(255, 255, 255, 0.35));
                -webkit-mask:
                    linear-gradient(#fff 0 0) content-box,
                    linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask:
                    linear-gradient(#fff 0 0) content-box,
                    linear-gradient(#fff 0 0);
                mask-composite: exclude;
                pointer-events: none;
                z-index: 0;
            }

            header.edica-header .header-glass > * {
                position: relative;
                z-index: 1;
            }

            header.edica-header .header-glass .navbar {
                display: flex;
                flex-wrap: nowrap;
                align-items: center;
                width: 100%;
                min-width: 0;
                padding-top: 1.85rem;
                padding-bottom: 1.85rem;
            }

            header.edica-header .header-brand-wrap {
                display: none;
                align-items: center;
                flex: 0 0 auto;
            }

            header.edica-header .navbar-brand.header-brand {
                display: inline-flex;
                align-items: center;
                gap: 0;
                margin: 0 1.25rem 0 0.75rem;
                padding: 0;
                line-height: 1;
                text-decoration: none;
            }

            header.edica-header .header-logo-mark {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 auto;
                line-height: 0;
            }

            header.edica-header .header-logo-mark img {
                display: block;
                transform-origin: 50% 50%;
                will-change: transform;
            }

            header.edica-header .header-logo-mark:hover img,
            header.edica-header .header-logo-mark:focus img {
                animation: header-logo-propeller 0.85s linear infinite;
            }

            header.edica-header .header-brand-text {
                display: inline-flex;
                align-items: center;
                color: #111827;
                font-size: 1.2rem;
                font-weight: 700;
                letter-spacing: 0.03em;
                line-height: 1;
                text-transform: uppercase;
            }

            @keyframes header-logo-propeller {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            @media (prefers-reduced-motion: reduce) {
                header.edica-header .header-logo-mark:hover img,
                header.edica-header .header-logo-mark:focus img {
                    animation: none;
                }
            }

            header.edica-header .header-glass .navbar-collapse {
                min-width: 0;
                align-items: center;
            }

            @media (min-width: 992px) {
                header.edica-header .header-brand-wrap {
                    display: inline-flex;
                }

                header.edica-header .header-glass .edica-main-nav {
                    flex-direction: row;
                    align-items: center;
                    margin-top: 0 !important;
                }

                header.edica-header .header-glass .edica-main-nav > .nav-item {
                    display: flex;
                    align-items: center;
                }

                header.edica-header .header-glass .edica-main-nav > .nav-item > .nav-link {
                    display: inline-flex;
                    align-items: center;
                    min-height: 2.35rem;
                    padding-top: 0;
                    padding-bottom: 0;
                    line-height: 1.2;
                }

                header.edica-header .header-glass .button-login,
                header.edica-header .header-glass .button-lid-create {
                    align-self: center;
                }
            }

            header.edica-header .header-glass .nav-item-login {
                margin-left: 1.25rem;
            }

            header.edica-header .header-glass .button-login {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.45rem;
                min-height: 2.35rem;
                padding: 0.45rem 1.15rem;
                border: 1px solid rgba(9, 76, 161, 0.22);
                border-radius: 999px;
                color: #094ca1;
                font-size: 0.95rem;
                font-weight: 700;
                line-height: 1.2;
                white-space: nowrap;
                text-decoration: none;
                background:
                    linear-gradient(
                        145deg,
                        rgba(255, 255, 255, 0.95) 0%,
                        rgba(236, 244, 255, 0.88) 48%,
                        rgba(210, 228, 252, 0.92) 100%
                    );
                box-shadow:
                    0 8px 18px rgba(9, 76, 161, 0.12),
                    inset 0 1px 0 rgba(255, 255, 255, 0.95);
                transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease, background 0.15s ease, color 0.15s ease;
            }

            header.edica-header .header-glass .button-login i {
                font-size: 0.85em;
                opacity: 0.9;
            }

            header.edica-header .header-glass .button-login:hover,
            header.edica-header .header-glass .button-login:focus {
                color: #083d82;
                border-color: rgba(9, 76, 161, 0.38);
                background:
                    linear-gradient(
                        145deg,
                        rgba(255, 255, 255, 1) 0%,
                        rgba(220, 234, 252, 0.96) 50%,
                        rgba(186, 214, 246, 0.98) 100%
                    );
                box-shadow:
                    0 12px 24px rgba(9, 76, 161, 0.18),
                    inset 0 1px 0 rgba(255, 255, 255, 1);
                text-decoration: none;
                transform: translateY(-1px);
            }

            header.edica-header .header-glass .button-lid-create {
                padding: 5px 12px;
                box-shadow: 0 6px 14px rgba(220, 53, 69, 0.18);
            }

            header.edica-header .header-glass .nav-item-lid-create {
                margin-right: 0.75rem !important;
                margin-left: 0.75rem !important;
            }

            header.edica-header .header-glass .navbar-nav > .nav-item:last-child {
                margin-right: 0;
                padding-right: 0;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            header.edica-header .header-glass {
                max-width: 960px;
            }
        }

        header.edica-header .navbar-nav .nav-item-user .header-user-toggle {
            gap: 0.55rem;
            padding: 0.35rem 0.65rem;
            min-height: 0;
            border: 1px solid rgba(9, 76, 161, 0.12);
            border-radius: 999px;
            background:
                linear-gradient(
                    145deg,
                    rgba(255, 255, 255, 0.9) 0%,
                    rgba(236, 244, 255, 0.75) 100%
                );
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }

        header.edica-header .navbar-nav .nav-item-user .header-user-toggle:hover,
        header.edica-header .navbar-nav .nav-item-user.show > .header-user-toggle {
            border-color: rgba(9, 76, 161, 0.28);
            background:
                linear-gradient(
                    145deg,
                    rgba(255, 255, 255, 0.98) 0%,
                    rgba(220, 234, 252, 0.92) 100%
                );
            box-shadow:
                0 8px 18px rgba(9, 76, 161, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 1);
        }

        header.edica-header .header-user-avatar {
            width: 28px;
            height: 28px;
            object-fit: cover;
            flex-shrink: 0;
        }

        header.edica-header .header-user-avatar--placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(9, 76, 161, 0.12);
            color: #094ca1;
            font-size: 0.75rem;
        }

        header.edica-header .header-user-name {
            max-width: 10rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 700;
            color: #0f172a;
        }

        header.edica-header .header-courses-menu {
            min-width: 22rem;
            max-width: min(28rem, calc(100vw - 2rem));
            margin-top: 0.55rem;
            padding: 0.45rem;
            border: 1px solid rgba(9, 76, 161, 0.12);
            border-radius: 1rem;
            background:
                linear-gradient(
                    145deg,
                    rgba(255, 255, 255, 0.98) 0%,
                    rgba(248, 251, 255, 0.97) 45%,
                    rgba(240, 246, 252, 0.98) 100%
                );
            backdrop-filter: blur(10px) saturate(140%);
            -webkit-backdrop-filter: blur(10px) saturate(140%);
            box-shadow:
                0 18px 40px rgba(15, 23, 42, 0.14),
                0 4px 14px rgba(9, 76, 161, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
        }

        header.edica-header .header-courses-menu .dropdown-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem 0.9rem;
            border-radius: 0.75rem;
            color: #334155;
            font-weight: 600;
            font-size: 0.92rem;
            line-height: 1.35;
            white-space: normal;
            background: transparent;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        header.edica-header .header-courses-menu .dropdown-item i {
            width: 1rem;
            margin-top: 0.15rem;
            color: #094ca1;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.9;
        }

        header.edica-header .header-courses-menu .dropdown-item:hover,
        header.edica-header .header-courses-menu .dropdown-item:focus {
            color: #094ca1;
            background: rgba(9, 76, 161, 0.08);
            text-decoration: none;
        }

        header.edica-header .header-user-menu {
            min-width: 15.5rem;
            margin-top: 0.55rem;
            padding: 0.45rem;
            border: 1px solid rgba(9, 76, 161, 0.12);
            border-radius: 1rem;
            background:
                linear-gradient(
                    145deg,
                    rgba(255, 255, 255, 0.98) 0%,
                    rgba(248, 251, 255, 0.97) 45%,
                    rgba(240, 246, 252, 0.98) 100%
                );
            backdrop-filter: blur(10px) saturate(140%);
            -webkit-backdrop-filter: blur(10px) saturate(140%);
            box-shadow:
                0 18px 40px rgba(15, 23, 42, 0.14),
                0 4px 14px rgba(9, 76, 161, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
        }

        header.edica-header .header-user-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem 0.85rem;
            border-radius: 0.7rem;
            color: #334155;
            font-weight: 600;
            font-size: 0.92rem;
            line-height: 1.25;
            background: transparent;
            border: 0;
            width: 100%;
            text-align: left;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        header.edica-header .header-user-menu .dropdown-item i {
            width: 1rem;
            color: #094ca1;
            text-align: center;
            opacity: 0.9;
        }

        header.edica-header .header-user-menu .dropdown-item:hover,
        header.edica-header .header-user-menu .dropdown-item:focus {
            color: #094ca1;
            background: rgba(9, 76, 161, 0.08);
            text-decoration: none;
        }

        header.edica-header .header-user-menu .dropdown-divider {
            margin: 0.35rem 0.4rem;
            border-top-color: rgba(9, 76, 161, 0.12);
        }

        header.edica-header .header-user-menu .header-user-menu__logout {
            color: #b42318;
        }

        header.edica-header .header-user-menu .header-user-menu__logout i {
            color: #b42318;
        }

        header.edica-header .header-user-menu .header-user-menu__logout:hover,
        header.edica-header .header-user-menu .header-user-menu__logout:focus {
            color: #912018;
            background: rgba(180, 35, 24, 0.08);
        }

        @media (min-width: 992px) {
            header.edica-header .nav-item-user:hover > .header-user-menu,
            header.edica-header .nav-item-user.show > .header-user-menu {
                display: block;
            }
        }

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
            header.edica-header.sticky-top {
                padding: 0;
                background-color: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            header.edica-header .header-glass {
                max-width: none;
                margin: 0;
                padding: 0 15px;
                border: 0;
                border-radius: 0;
                background: transparent;
                box-shadow: none;
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
            }

            header.edica-header .header-glass::before {
                content: none;
            }

            header.edica-header .navbar-nav .nav-item.active .nav-link,
            header.edica-header .navbar-nav .nav-link.active {
                color: #094ca1;
                border-color: #d0d5db;
            }

            header.edica-header .navbar-nav .nav-item-user {
                width: 100%;
            }

            header.edica-header .navbar-nav .nav-item-user .header-user-toggle {
                width: 100%;
                justify-content: center;
                border-radius: 0.8rem;
            }

            header.edica-header .navbar-nav .nav-item-user .header-user-menu {
                width: 100%;
                margin-top: 0.45rem;
                position: static !important;
                float: none;
                transform: none !important;
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
<header class="edica-header sticky-top">
    <div class="header-glass">
        <nav class="navbar navbar-expand-lg navbar-light">

            <div class="header-brand-wrap d-none d-lg-flex">
                <a class="navbar-brand header-brand" href="{{ route('main.index') }}" aria-label="Основание">
                    <span class="header-logo-mark">
                        <img src="{{ asset('assets/images/logo-main-little.png') }}" width="40" height="40" alt="">
                    </span>
                    <span class="header-brand-text">Основание</span>
                </a>
            </div>
            @php
                $mobileHeaderCta = null;
                if (request()->routeIs('course.show') && isset($course) && empty($course->bioeconomy)) {
                    $isPaidCourse = isset($course->price) && $course->price != 0;
                    $mobileHeaderCta = [
                        'label' => $isPaidCourse ? 'Заказ' : 'Записаться',
                        'url' => $isPaidCourse
                            ? route('order.create', $course->id)
                            : route('lid.create', ['selectedCourse' => $course->id]),
                        'class' => 'mobile-header-cta--enroll',
                        'icon' => true,
                    ];
                } elseif (request()->routeIs('bioeconomy.index')) {
                    $mobileHeaderCta = [
                        'label' => 'Заявка',
                        'url' => route('org.bioeconomy.create'),
                        'class' => 'mobile-header-cta--enroll',
                        'icon' => false,
                    ];
                } elseif (request()->routeIs('course.index')) {
                    $mobileHeaderCta = [
                        'label' => 'Заявка',
                        'url' => route('lid.create'),
                        'class' => 'btn btn-danger',
                        'icon' => false,
                    ];
                }
            @endphp
            <div class="d-lg-none mobile-header-bar{{ $mobileHeaderCta ? ' mobile-header-bar--with-cta' : '' }}">
                <a class="navbar-brand mobile-header-logo header-logo-mark mb-0" href="{{route('main.index')}}" aria-label="Основание">
                    <img src="{{asset('assets/images/logo-main-little.png')}}" width="40" height="40" alt="">
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
                        <div class="dropdown-menu header-courses-menu" aria-labelledby="blogDropdown">
{{--                            <a class="dropdown-item" href="{{route('future.index')}}">Код будущего</a>--}}
                            <a class="dropdown-item" href="{{route('course.index')}}">
                                <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                                <span>Бесплатные курсы «Активные меры содействия занятости»</span>
                            </a>
                            <a class="dropdown-item" href="{{route('commerce.index')}}">
                                <i class="fas fa-briefcase" aria-hidden="true"></i>
                                <span>Софинансирование обучения «Профессии будущего»</span>
                            </a>
                            <a class="dropdown-item" href="{{route('archive.index')}}">
                                <i class="fas fa-archive" aria-hidden="true"></i>
                                <span>Архив</span>
                            </a>
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
                        <a class="nav-link header-social-btn header-social-btn--max" href="{{url('https://max.ru/id7751117260_biz')}}" target="_blank" rel="noopener noreferrer" aria-label="Макс">
                            <svg class="header-social-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                                <path fill="currentColor" fill-rule="evenodd" d="M12 2.2C6.7 2.2 2.4 6.3 2.4 11.35c0 2.9 1.35 5.5 3.5 7.25L4.1 21.8l3.6-1.4c1.3.5 2.75.8 4.3.8 5.3 0 9.6-4.1 9.6-9.15S17.3 2.2 12 2.2Zm0 3.35c3.55 0 6.4 2.7 6.4 6.05S15.55 17.65 12 17.65c-1.1 0-2.15-.25-3.05-.7l-2.45.95.9-2.2c-1.15-1.15-1.85-2.7-1.85-4.4 0-3.35 2.85-6.05 6.45-6.05Z"/>
                            </svg>
                            <span class="d-lg-none">Макс</span>
                        </a>
                    </li>
                    <li class="nav-item nav-item-social">
                        <a class="nav-link header-social-btn header-social-btn--telegram" href="{{url('https://t.me/osnovanie_study')}}" target="_blank" rel="noopener noreferrer" aria-label="Телеграм">
                            <i class="fab fa-telegram-plane header-social-icon" aria-hidden="true"></i>
                            <span class="d-lg-none">Телеграм</span>
                        </a>
                    </li>
                    @endguest

                    @if( isset(auth()->user()->id) )
                        @php
                            $userCabinetUrl = match ((int) auth()->user()->role) {
                                1 => route('admin.main.index'),
                                2 => route('agent.main.index'),
                                3 => route('cc.main.index'),
                                default => route('user.index'),
                            };
                            $userCabinetLabel = in_array((int) auth()->user()->role, [1, 3], true)
                                ? 'Административная панель'
                                : 'Личный кабинет';
                        @endphp

                        <li class="nav-item dropdown nav-item-user ml-lg-auto">
                            <a class="nav-link dropdown-toggle header-user-toggle d-inline-flex align-items-center" href="#" id="userMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(auth()->user()->avatar)
                                    <img src="{{ url('storage/' . auth()->user()->avatar) }}" alt="" class="header-user-avatar rounded-circle" width="28" height="28">
                                @else
                                    <span class="header-user-avatar header-user-avatar--placeholder" aria-hidden="true">
                                        <i class="fas fa-user"></i>
                                    </span>
                                @endif
                                <span class="header-user-name">{{ auth()->user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg-right header-user-menu" aria-labelledby="userMenuDropdown">
                                <a class="dropdown-item" href="{{ $userCabinetUrl }}">
                                    <i class="fas fa-th-large" aria-hidden="true"></i>{{ $userCabinetLabel }}
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit" aria-hidden="true"></i>Профиль
                                </a>
                                @if((int) auth()->user()->role === 10)
                                    <a class="dropdown-item" href="{{ route('user.documents') }}">
                                        <i class="fas fa-folder-open" aria-hidden="true"></i>Документы
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('help.index') }}">
                                    <i class="fas fa-question-circle" aria-hidden="true"></i>Справка
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item header-user-menu__logout">
                                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>Выйти
                                    </button>
                                </form>
                            </div>
                        </li>

                    @else
                        @php
                            $hideHeaderLidCreate = request()->routeIs(
                                'main.index',
                                'about.index',
                                'login',
                                'author.*',
                                'org.bioeconomy.create',
                                'lid.bioeconomy.personal.create',
                                'lid.create'
                            ) || (request()->routeIs('course.show') && isset($course) && !empty($course->bioeconomy));

                            $headerLidCreateLabel = 'Заявка';
                            $headerLidCreateUrl = route('lid.create');
                            $headerLidCreateClass = '';

                            if (request()->routeIs('bioeconomy.index')) {
                                $headerLidCreateUrl = route('org.bioeconomy.create');
                                $headerLidCreateClass = ' button-lid-create--bio';
                            } elseif (
                                request()->routeIs('course.show')
                                && isset($course)
                                && isset($course->price)
                                && $course->price != 0
                            ) {
                                $headerLidCreateLabel = 'Заказ';
                                $headerLidCreateUrl = route('order.create', $course->id);
                            }
                        @endphp
                        @unless($hideHeaderLidCreate)
                        <li class="nav-item nav-item-lid-create mr-lg-3 ml-lg-3">
                            <a class="button-lid-create{{ $headerLidCreateClass }}" role="button" href="{{ $headerLidCreateUrl }}">{{ $headerLidCreateLabel }}</a>
                        </li>
                        @endunless

                        @unless(request()->routeIs('login'))
                        <li class="nav-item nav-item-login">
                            <a class="nav-link d-lg-none" href="{{route('login')}}">Войти</a>
                            <a class="button-login d-none d-lg-inline-flex" href="{{route('login')}}"><i class="fas fa-sign-in-alt" aria-hidden="true"></i>Войти</a>
                        </li>
                        @endunless
                    @endif

                </ul>

            </div>
        </nav>

    </div>
</header>

<div class="flex-grow-1 w-100">
@yield('content')
</div>

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

@stack('scripts')
@include('cookie-consent::index')
</body>
</html>

