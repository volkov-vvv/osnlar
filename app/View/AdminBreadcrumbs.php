<?php

namespace App\View;

use Illuminate\Support\Facades\Route;

class AdminBreadcrumbs
{
    private const RESOURCES = [
        'main' => 'Дашборд',
        'contactcenter' => 'Контакт-центр',
        'user' => 'Пользователи',
        'company' => 'Компании',
        'about' => 'Основные сведения',
        'document' => 'Документы',
        'course' => 'Курсы',
        'studygroup' => 'Учебные группы',
        'author' => 'Авторы',
        'contract' => 'Договоры',
        'category' => 'Категории',
        'leveledu' => 'Уровни образования',
        'region' => 'Регионы',
        'link' => 'Ссылки',
        'agent' => 'Агенты',
        'status' => 'Статусы',
        'lid' => 'Заявки',
        'biolid' => 'Заявки по БИО',
        'commerciallid' => 'Коммерческие заявки',
        'org' => 'Заявки организаций',
        'order' => 'Заказы',
        'report' => 'Отчеты',
        'year' => 'Годы',
        'project' => 'Проекты',
    ];

    private const GROUPS = [
        'about' => 'О нас',
        'document' => 'О нас',
        'year' => 'Настройки',
        'project' => 'Настройки',
    ];

    private const ACTIONS = [
        'index' => null,
        'create' => 'Создание',
        'store' => 'Создание',
        'show' => 'Просмотр',
        'edit' => 'Редактирование',
        'update' => 'Редактирование',
    ];

    private const SPECIAL = [
        'admin.main.index' => [
            ['title' => 'Дашборд', 'url' => null],
        ],
        'admin.contactcenter.index' => [
            ['title' => 'Дашборд', 'url' => 'admin.main.index'],
            ['title' => 'Контакт-центр', 'url' => null],
        ],
        'admin.settings.forms.edit' => [
            ['title' => 'Дашборд', 'url' => 'admin.main.index'],
            ['title' => 'Настройки', 'url' => null],
            ['title' => 'Формы', 'url' => null],
        ],
        'admin.settings.bioeconomy.edit' => [
            ['title' => 'Дашборд', 'url' => 'admin.main.index'],
            ['title' => 'Настройки', 'url' => null],
            ['title' => 'Биоэкономика', 'url' => null],
        ],
    ];

    /**
     * @return list<array{title: string, url: ?string}>
     */
    public static function items(?array $override = null): array
    {
        if (is_array($override) && $override !== []) {
            return self::resolveUrls($override);
        }

        $routeName = Route::currentRouteName();
        if (! is_string($routeName) || ! str_starts_with($routeName, 'admin.')) {
            return [];
        }

        if (isset(self::SPECIAL[$routeName])) {
            return self::resolveUrls(self::SPECIAL[$routeName]);
        }

        $parts = explode('.', $routeName);
        // admin.{resource}.{action} or admin.report.index
        if (count($parts) < 3) {
            return self::resolveUrls([
                ['title' => 'Дашборд', 'url' => 'admin.main.index'],
            ]);
        }

        $resource = $parts[1];
        $action = $parts[array_key_last($parts)];

        if (! isset(self::RESOURCES[$resource])) {
            return self::resolveUrls([
                ['title' => 'Дашборд', 'url' => 'admin.main.index'],
            ]);
        }

        $items = [
            ['title' => 'Дашборд', 'url' => 'admin.main.index'],
        ];

        if (isset(self::GROUPS[$resource])) {
            $items[] = ['title' => self::GROUPS[$resource], 'url' => null];
        }

        $indexRoute = 'admin.'.$resource.'.index';
        $actionLabel = self::ACTIONS[$action] ?? null;

        if ($action === 'index' || ! Route::has($indexRoute)) {
            $items[] = ['title' => self::RESOURCES[$resource], 'url' => null];
        } else {
            $items[] = ['title' => self::RESOURCES[$resource], 'url' => $indexRoute];
            $items[] = ['title' => $actionLabel ?? 'Страница', 'url' => null];
        }

        return self::resolveUrls($items);
    }

    /**
     * @param  list<array{title: string, url: ?string}>  $items
     * @return list<array{title: string, url: ?string}>
     */
    private static function resolveUrls(array $items): array
    {
        return array_map(static function (array $item): array {
            $url = $item['url'] ?? null;
            if (is_string($url) && $url !== '' && ! str_starts_with($url, 'http') && ! str_starts_with($url, '/')) {
                $url = Route::has($url) ? route($url) : null;
            }

            return [
                'title' => $item['title'],
                'url' => $url,
            ];
        }, $items);
    }
}
