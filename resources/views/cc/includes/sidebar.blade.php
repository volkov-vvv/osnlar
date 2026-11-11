<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('main.index') }}" class="brand-link">
        <img src="{{ asset('dist/img/logoCRM.png') }}" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light">ОСНОВАНИЕ CRM</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('cc.main.index') }}" class="nav-link {{ request()->routeIs('cc.main.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Дашборд</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('cc.lid.*', 'cc.biolid.*', 'cc.org.*', 'cc.order.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('cc.lid.*', 'cc.biolid.*', 'cc.org.*', 'cc.order.*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-bell"></i>
                        <p>
                            Заявки
                            <i class="right nav-tree-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('cc.lid.index') }}" class="nav-link {{ request()->routeIs('cc.lid.*') ? 'active' : '' }}">
                                <i class="fas fa-user-graduate nav-icon"></i>
                                <p>Заявки АМСЗ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cc.biolid.index') }}" class="nav-link {{ request()->routeIs('cc.biolid.*') ? 'active' : '' }}">
                                <i class="fas fa-leaf nav-icon"></i>
                                <p>Заявки БИО</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cc.org.index') }}" class="nav-link {{ request()->routeIs('cc.org.*') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Заявки организаций</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cc.order.index') }}" class="nav-link {{ request()->routeIs('cc.order.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart nav-icon"></i>
                                <p>Заказы</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('cc.link.index') }}" class="nav-link {{ request()->routeIs('cc.link.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-link"></i>
                        <p>Ссылки</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cc.status.index') }}" class="nav-link {{ request()->routeIs('cc.status.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exclamation-circle"></i>
                        <p>Статусы</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
