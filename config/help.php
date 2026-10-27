<?php

use App\Models\User;

/**
 * Web help pages sourced from docs/*.md
 * roles: which User::ROLE_* can open the page
 */
return [
    'pages' => [
        [
            'slug' => 'roles',
            'title' => 'Роли и вход',
            'group' => 'База',
            'file' => '00-roles-and-login.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_AGENT, User::ROLE_CONTACT, User::ROLE_USER],
        ],
        [
            'slug' => 'courses',
            'title' => 'Курсы',
            'group' => 'Модули',
            'file' => 'modules/courses.md',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'slug' => 'study-groups',
            'title' => 'Учебные группы',
            'group' => 'Модули',
            'file' => 'modules/study-groups.md',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'slug' => 'biolids',
            'title' => 'Заявки по БИО',
            'group' => 'Модули',
            'file' => 'modules/biolids.md',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'slug' => 'lids',
            'title' => 'Заявки (слушатели)',
            'group' => 'Модули',
            'file' => 'modules/lids.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_CONTACT],
        ],
        [
            'slug' => 'orgs',
            'title' => 'Заявки организаций',
            'group' => 'Модули',
            'file' => 'modules/orgs.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_CONTACT],
        ],
        [
            'slug' => 'orders',
            'title' => 'Заказы',
            'group' => 'Модули',
            'file' => 'modules/orders.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_CONTACT],
        ],
        [
            'slug' => 'users',
            'title' => 'Пользователи',
            'group' => 'Модули',
            'file' => 'modules/users.md',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'slug' => 'authors-contracts',
            'title' => 'Авторы и договоры',
            'group' => 'Модули',
            'file' => 'modules/authors-contracts.md',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'slug' => 'dictionaries',
            'title' => 'Справочники',
            'group' => 'Модули',
            'file' => 'modules/dictionaries.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_CONTACT],
        ],
        [
            'slug' => 'reports',
            'title' => 'Отчёты',
            'group' => 'Модули',
            'file' => 'modules/reports.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_AGENT],
        ],
        [
            'slug' => 'settings',
            'title' => 'Настройки',
            'group' => 'Модули',
            'file' => 'modules/settings.md',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'slug' => 'public-site',
            'title' => 'Публичный сайт и формы',
            'group' => 'Модули',
            'file' => 'modules/public-site.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER],
        ],
        [
            'slug' => 'user-cabinet',
            'title' => 'Личный кабинет слушателя',
            'group' => 'Модули',
            'file' => 'modules/user-cabinet.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER],
        ],
        [
            'slug' => 'agent',
            'title' => 'Кабинет агента',
            'group' => 'Модули',
            'file' => 'modules/agent.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_AGENT],
        ],
        [
            'slug' => 'bioeconomy-onboarding',
            'title' => 'Сценарий: запуск курса по биоэкономике',
            'group' => 'Сценарии',
            'file' => 'flows/bioeconomy-onboarding.md',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'slug' => 'lid-to-cc',
            'title' => 'Сценарий: заявка с сайта до КЦ',
            'group' => 'Сценарии',
            'file' => 'flows/lid-to-cc.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_CONTACT],
        ],
        [
            'slug' => 'order-and-documents',
            'title' => 'Сценарий: платный заказ и документы',
            'group' => 'Сценарии',
            'file' => 'flows/order-and-documents.md',
            'roles' => [User::ROLE_ADMIN, User::ROLE_CONTACT, User::ROLE_USER],
        ],
    ],
];
