<?php

use App\Http\Controllers\CC\DashboardController;
use App\Http\Controllers\CC\LidController;
use App\Http\Controllers\CC\LinkController;
use App\Http\Controllers\CC\Main\IndexController as MainIndexController;
use App\Http\Controllers\CC\OrderController;
use App\Http\Controllers\CC\Org\DeleteController as OrgDeleteController;
use App\Http\Controllers\CC\Org\EditController as OrgEditController;
use App\Http\Controllers\CC\Org\IndexController as OrgIndexController;
use App\Http\Controllers\CC\Org\ShowController as OrgShowController;
use App\Http\Controllers\CC\Org\UpdateController as OrgUpdateController;
use App\Http\Controllers\CC\Status\CreateController as StatusCreateController;
use App\Http\Controllers\CC\Status\DeleteController as StatusDeleteController;
use App\Http\Controllers\CC\Status\EditController as StatusEditController;
use App\Http\Controllers\CC\Status\IndexController as StatusIndexController;
use App\Http\Controllers\CC\Status\ShowController as StatusShowController;
use App\Http\Controllers\CC\Status\StoreController as StatusStoreController;
use App\Http\Controllers\CC\Status\UpdateController as StatusUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainIndexController::class)->name('main.index');

Route::get('/getDashboard', [DashboardController::class, 'getDashboard'])
    ->name('dashboard.getDashboard');

Route::invokableCrud('status', 'status', [
    'index' => StatusIndexController::class,
    'create' => StatusCreateController::class,
    'store' => StatusStoreController::class,
    'show' => StatusShowController::class,
    'edit' => StatusEditController::class,
    'update' => StatusUpdateController::class,
    'delete' => StatusDeleteController::class,
]);

Route::invokableCrud('org', 'org', [
    'index' => OrgIndexController::class,
    'show' => OrgShowController::class,
    'edit' => OrgEditController::class,
    'update' => OrgUpdateController::class,
    'delete' => OrgDeleteController::class,
], [
    'only' => ['index', 'show', 'edit', 'update', 'delete'],
]);

Route::get('/lid/getLids', [LidController::class, 'getLids'])->name('lid.getLids');
Route::get('/lid/getLidsExcel', [LidController::class, 'getLidsExcel'])->name('lid.getLidsExcel');
Route::resource('lid', LidController::class)->whereNumber('lid');
Route::resource('order', OrderController::class)->whereNumber('order');
Route::resource('link', LinkController::class)->whereNumber('link');
