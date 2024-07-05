<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{$pageDescription}}"/>

    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('assets/images/fav.ico')}}">

    <title>Заявка на обучение</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- Phone input -->
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">

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



</head>
<body>

<header>
    <div class="container ">
        <header class="d-flex justify-content-center pt-3">
            <a class="navbar-brand" href="{{route('main.index')}}"><img src="{{asset('assets/images/logo-main.png')}}" width="260"  alt="Основание"></a>
        </header>
    </div>
</header>

    <!-- Content Wrapper. Contains page content -->
    <div class="content">
        <!-- Content Header (Page header) -->
        @yield('content')
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->




<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
<script src="{{ asset('plugins/select2/js/i18n/ru.js') }}"></script>

<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js')  }} "></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js')}} "></script>

<!-- Phone input -->
<script src="{{ asset('js/intlTelInput/intlTelInput.min.js')}} "></script>
<script src="{{ asset('js/intlTelInput/data.min.js')}} "></script>

<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>

<script src="https://kit.fontawesome.com/068a35c10e.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {



        $('[data-mask]').inputmask();

        var input = document.querySelector("#phone");
        const iti = window.intlTelInput(input, {
            strictMode: true,
            showSelectedDialCode: true,
            nationalMode: false,
            initialCountry:"ru",
            onlyCountries: ["ru", "by"],
            i18n: {
                // Country names
                ru: "Россия",
                by: "Беларусь",
            },
            hiddenInput: () => ({ phone: "phone"}),
            utilsScript: "{{ asset('js/intlTelInput/utils.js')}}?1712939239769"
        });

        input.addEventListener('countrychange', () => {
            $('#phone_prefix').val(iti.getSelectedCountryData().dialCode);
        });
    });


        var stepper = new Stepper(document.querySelector('.bs-stepper'));


    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            language: "ru"
        })
    })


</script>

@yield('javascript')


</body>
</html>
