<?php

use Illuminate\Support\Facades\Route;

/**
 * Register invokable single-action CRUD routes.
 *
 * Expected $controllers keys: index, create, store, show, edit, update, delete.
 * Optional $options: only (array), parameter (string), whereNumber (bool, default true).
 */
Route::macro('invokableCrud', function (string $uri, string $name, array $controllers, array $options = []) {
    $parameter = $options['parameter'] ?? $uri;
    $only = $options['only'] ?? ['index', 'create', 'store', 'show', 'edit', 'update', 'delete'];
    $whereNumber = $options['whereNumber'] ?? true;

    $paramRoute = function (string $action, string $method, string $path, string $routeName) use ($controllers, $parameter, $whereNumber) {
        $route = Route::{$method}($path, $controllers[$action])->name($routeName);
        if ($whereNumber && strpos($path, '{') !== false) {
            $route->whereNumber($parameter);
        }

        return $route;
    };

    if (in_array('index', $only, true)) {
        Route::get($uri, $controllers['index'])->name("{$name}.index");
    }
    if (in_array('create', $only, true)) {
        Route::get("{$uri}/create", $controllers['create'])->name("{$name}.create");
    }
    if (in_array('store', $only, true)) {
        Route::post($uri, $controllers['store'])->name("{$name}.store");
    }
    if (in_array('show', $only, true)) {
        $paramRoute('show', 'get', "{$uri}/{{$parameter}}", "{$name}.show");
    }
    if (in_array('edit', $only, true)) {
        $paramRoute('edit', 'get', "{$uri}/{{$parameter}}/edit", "{$name}.edit");
    }
    if (in_array('update', $only, true)) {
        $paramRoute('update', 'patch', "{$uri}/{{$parameter}}", "{$name}.update");
    }
    if (in_array('delete', $only, true)) {
        $paramRoute('delete', 'delete', "{$uri}/{{$parameter}}", "{$name}.delete");
    }
});
