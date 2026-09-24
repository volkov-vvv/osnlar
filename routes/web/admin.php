<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommercialLidController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\LeveleduController;
use App\Http\Controllers\Admin\LidController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\Main\IndexController as MainIndexController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\Report\IndexController as ReportIndexController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainIndexController::class)->name('main.index');

Route::get('/getDashboard', [DashboardController::class, 'getDashboard'])
    ->name('dashboard.getDashboard');

Route::prefix('report')->name('report.')->group(function () {
    Route::get('/', ReportIndexController::class)->name('index');
    Route::get('/getReport', [ReportIndexController::class, 'getReport'])->name('getReport');
    Route::get('/getReportExcel', [ReportIndexController::class, 'getReportExcel'])->name('getReportExcel');
});

Route::resource('user', UserController::class)->whereNumber('user');
Route::resource('about', AboutController::class)->whereNumber('about');
Route::resource('document', DocumentController::class)->whereNumber('document');
Route::resource('course', CourseController::class)->whereNumber('course');
Route::resource('category', CategoryController::class)->whereNumber('category');
Route::resource('author', AuthorController::class)->whereNumber('author');
Route::resource('agent', AgentController::class)->whereNumber('agent');
Route::resource('leveledu', LeveleduController::class)->whereNumber('leveledu');
Route::resource('region', RegionController::class)->whereNumber('region');
Route::resource('status', StatusController::class)->whereNumber('status');

Route::get('/lid/getLids', [LidController::class, 'getLids'])->name('lid.getLids');
Route::get('/lid/getLidsExcel', [LidController::class, 'getLidsExcel'])->name('lid.getLidsExcel');
Route::resource('lid', LidController::class)->whereNumber('lid');

Route::get('/commerciallid/getLids', [CommercialLidController::class, 'getLids'])
    ->name('commerciallid.getLids');
Route::resource('commerciallid', CommercialLidController::class)->whereNumber('commerciallid');

Route::resource('order', OrderController::class)->whereNumber('order');
Route::resource('company', CompanyController::class)->whereNumber('company');
Route::resource('link', LinkController::class)->whereNumber('link');
