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
                    <a href="{{ route('agent.main.index') }}" class="nav-link {{ request()->routeIs('agent.main.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Дашборд</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('agent.report.*', 'agent.biolid.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('agent.report.*', 'agent.biolid.*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-bell"></i>
                        <p>
                            Заявки
                            <i class="right nav-tree-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('agent.biolid.index') }}" class="nav-link {{ request()->routeIs('agent.biolid.*') ? 'active' : '' }}">
                                <i class="fas fa-leaf nav-icon"></i>
                                <p>Заявки БИО</p>
                            </a>
                        </li>
                        @if(auth()->user()->hasAgentReportAccess())
                        <li class="nav-item">
                            <a href="{{ route('agent.report.index') }}" class="nav-link {{ request()->routeIs('agent.report.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <p>Отчеты</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
