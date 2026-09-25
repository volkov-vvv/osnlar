<?php

use App\Http\Controllers\CC\DashboardController;
use App\Http\Controllers\CC\LidController;
use App\Http\Controllers\CC\Main\IndexController as MainIndexController;
use App\Http\Controllers\CC\OrgController;
use App\Http\Controllers\Common\LinkController;
use App\Http\Controllers\Common\OrderController;
use App\Http\Controllers\Common\StatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainIndexController::class)->name('main.index');

Route::get('/getDashboard', [DashboardController::class, 'getDashboard'])
    ->name('dashboard.getDashboard');

Route::resource('status', StatusController::class)->whereNumber('status');
Route::resource('org', OrgController::class)
    ->except(['create', 'store'])
    ->whereNumber('org');

Route::get('/lid/getLids', [LidController::class, 'getLids'])->name('lid.getLids');
Route::get('/lid/getLidsExcel', [LidController::class, 'getLidsExcel'])->name('lid.getLidsExcel');
Route::resource('lid', LidController::class)->whereNumber('lid');
Route::resource('order', OrderController::class)->whereNumber('order');
Route::resource('link', LinkController::class)->whereNumber('link');
