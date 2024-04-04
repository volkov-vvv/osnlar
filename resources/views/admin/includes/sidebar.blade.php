<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('main.index')}}" class="brand-link">
        <img src="{{asset('dist/img/logoCRM.png')}}" alt="Logo" class="brand-image"
             >
        <span class="brand-text font-weight-light">ОСНОВАНИЕ CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{auth()->user()->name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('admin.main.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-house"></i>
                        <p>
                            Дашборд
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.user.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-user-plus"></i>
                        <p>
                            Пользователи
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.course.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-book-open"></i>
                        <p>
                            Курсы
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.author.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-people-group"></i>
                        <p>
                            Авторы
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.category.index')}}" class="nav-link">
                        <i class="nav-icon fa fa-list"></i>
                        <p>
                            Категории
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.leveledu.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-user-graduate"></i>
                        <p>
                            Уровни образования
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.region.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-map-location-dot"></i>
                        <p>
                            Регионы
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.agent.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-magnet"></i>
                        <p>
                            Агенты
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.status.index')}}" class="nav-link">
                        <i class="nav-icon fa-solid fa-circle-exclamation"></i>
                        <p>
                            Статусы
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('lid.index')}}" class="nav-link">
                        <i class="nav-icon fa-regular fa-bell"></i>
                        <p>
                            Заявки
                        </p>
                    </a>
                </li>
            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
