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
                    <a href="{{route('cc.main.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Дашборд
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('cc.status.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-exclamation-circle"></i>
                        <p>
                            Статусы
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('cc.lid.index')}}" class="nav-link">
                        <i class="nav-icon far fa-bell"></i>
                        <p>
                            Заявки (слушатели)
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('cc.order.index')}}" class="nav-link">
                        <i class="nav-icon far fa-usd"></i>
                        <p>
                            Заказы
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('cc.org.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-building-columns fa-landmark"></i>
                        <p>
                            Заявки организации
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('cc.link.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-link"></i>
                        <p>
                            Ссылки
                        </p>
                    </a>
                </li>
            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
