
<nav id="my-class-main" class="navbar navbar-expand-lg" data-bs-theme="white">
        <a class="navbar-brand" href="{{route('main.index')}}"><img src="{{asset('assets/images/logo-main.png')}}"
                                                                    width="260" alt="Основание"></a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#edicaMainNav"
                aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="edicaMainNav">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('main.index')}}">Главная <span
                            class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('about.index')}}">О нас</a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('services.index')}}">Услуги</a>--}}
{{--                </li>--}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Курсы
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('future.index')}}">Код будущего</a></li>
                        <li><a class="dropdown-item" href="{{route('course.index')}}">Бесплатные курсы</a></li>
                        <li><a class="dropdown-item" href="{{route('commerce.index')}}">Платные курсы</a></li>
                        <li><a class="dropdown-item" href="{{route('archive.index')}}">Архив</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://lms.osnovanie.info/login/index.php">Платформа</a>
                </li>
            </ul>



            @if( isset(auth()->user()->id) )
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">{{auth()->user()->name}}</a>
                        <div class="dropdown-menu" aria-labelledby="blogDropdown">
                            <a class="dropdown-item btn btn-link" href="#">
                                Настройки
                            </a>
                            <a class="dropdown-item btn btn-link" href="
                            @switch(auth()->user()->role)
                            @case(1) {{auth()->user()->role == 1 ? route('admin.main.index') . ' ">Административная панель' : ''}}
                            @break
                            @case(2) {{auth()->user()->role == 3 ? route('cc.main.index') . ' ">Административная панель' : ''}}
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


