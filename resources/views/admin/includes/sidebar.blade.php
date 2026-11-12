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
                    <a href="{{ route('admin.main.index') }}" class="nav-link {{ request()->routeIs('admin.main.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Дашборд</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}" class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>Пользователи</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.report.*', 'admin.lid.*', 'admin.biolid.*', 'admin.org.*', 'admin.order.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.report.*', 'admin.lid.*', 'admin.biolid.*', 'admin.org.*', 'admin.order.*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-bell"></i>
                        <p>
                            Заявки
                            <i class="right nav-tree-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.lid.index') }}" class="nav-link {{ request()->routeIs('admin.lid.*') ? 'active' : '' }}">
                                <i class="fas fa-user-graduate nav-icon"></i>
                                <p>Заявки АМСЗ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.biolid.index') }}" class="nav-link {{ request()->routeIs('admin.biolid.*') ? 'active' : '' }}">
                                <i class="fas fa-leaf nav-icon"></i>
                                <p>Заявки БИО</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.org.index') }}" class="nav-link {{ request()->routeIs('admin.org.*') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Заявки организаций</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.order.index') }}" class="nav-link {{ request()->routeIs('admin.order.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart nav-icon"></i>
                                <p>Заказы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.report.index') }}" class="nav-link {{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <p>Отчеты</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.course.*', 'admin.studygroup.*', 'admin.author.*', 'admin.contract.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.course.*', 'admin.studygroup.*', 'admin.author.*', 'admin.contract.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>
                            Обучение
                            <i class="right nav-tree-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.course.index') }}" class="nav-link {{ request()->routeIs('admin.course.*') ? 'active' : '' }}">
                                <i class="fas fa-graduation-cap nav-icon"></i>
                                <p>Курсы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.studygroup.index') }}" class="nav-link {{ request()->routeIs('admin.studygroup.*') ? 'active' : '' }}">
                                <i class="fas fa-chalkboard-teacher nav-icon"></i>
                                <p>Учебные группы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.author.index') }}" class="nav-link {{ request()->routeIs('admin.author.*') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Авторы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.contract.index') }}" class="nav-link {{ request()->routeIs('admin.contract.*') ? 'active' : '' }}">
                                <i class="fas fa-file-contract nav-icon"></i>
                                <p>Договоры</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.category.*', 'admin.leveledu.*', 'admin.region.*', 'admin.link.*', 'admin.status.*', 'admin.company.*', 'admin.agent.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.category.*', 'admin.leveledu.*', 'admin.region.*', 'admin.link.*', 'admin.status.*', 'admin.company.*', 'admin.agent.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Справочники
                            <i class="right nav-tree-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.company.index') }}" class="nav-link {{ request()->routeIs('admin.company.*') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Компании</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.agent.index') }}" class="nav-link {{ request()->routeIs('admin.agent.*') ? 'active' : '' }}">
                                <i class="fas fa-handshake nav-icon"></i>
                                <p>Агенты</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.category.index') }}" class="nav-link {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                                <i class="fas fa-tags nav-icon"></i>
                                <p>Категории</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.leveledu.index') }}" class="nav-link {{ request()->routeIs('admin.leveledu.*') ? 'active' : '' }}">
                                <i class="fas fa-layer-group nav-icon"></i>
                                <p>Уровни образования</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.region.index') }}" class="nav-link {{ request()->routeIs('admin.region.*') ? 'active' : '' }}">
                                <i class="fas fa-map-marker-alt nav-icon"></i>
                                <p>Регионы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.link.index') }}" class="nav-link {{ request()->routeIs('admin.link.*') ? 'active' : '' }}">
                                <i class="fas fa-link nav-icon"></i>
                                <p>Ссылки</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.status.index') }}" class="nav-link {{ request()->routeIs('admin.status.*') ? 'active' : '' }}">
                                <i class="fas fa-exclamation-circle nav-icon"></i>
                                <p>Статусы</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.about.*', 'admin.document.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.about.*', 'admin.document.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-info-circle"></i>
                        <p>
                            О нас
                            <i class="right nav-tree-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.about.index') }}" class="nav-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                                <i class="fas fa-align-left nav-icon"></i>
                                <p>Основные сведения</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.document.index') }}" class="nav-link {{ request()->routeIs('admin.document.*') ? 'active' : '' }}">
                                <i class="fas fa-folder-open nav-icon"></i>
                                <p>Документы</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.year.*', 'admin.project.*', 'admin.settings.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.year.*', 'admin.project.*', 'admin.settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Настройки
                            <i class="right nav-tree-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.year.index') }}" class="nav-link {{ request()->routeIs('admin.year.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt nav-icon"></i>
                                <p>Годы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.project.index') }}" class="nav-link {{ request()->routeIs('admin.project.*') ? 'active' : '' }}">
                                <i class="fas fa-project-diagram nav-icon"></i>
                                <p>Проекты</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.forms.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.forms.*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>Формы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.bioeconomy.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.bioeconomy.*') ? 'active' : '' }}">
                                <i class="fas fa-seedling nav-icon"></i>
                                <p>Биоэкономика</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
