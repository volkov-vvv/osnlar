<?php

use App\Http\Controllers\Admin\About\CreateController as AboutCreateController;
use App\Http\Controllers\Admin\About\DeleteController as AboutDeleteController;
use App\Http\Controllers\Admin\About\EditController as AboutEditController;
use App\Http\Controllers\Admin\About\IndexController as AboutIndexController;
use App\Http\Controllers\Admin\About\ShowController as AboutShowController;
use App\Http\Controllers\Admin\About\StoreController as AboutStoreController;
use App\Http\Controllers\Admin\About\UpdateController as AboutUpdateController;
use App\Http\Controllers\Admin\Agent\CreateController as AgentCreateController;
use App\Http\Controllers\Admin\Agent\DeleteController as AgentDeleteController;
use App\Http\Controllers\Admin\Agent\EditController as AgentEditController;
use App\Http\Controllers\Admin\Agent\IndexController as AgentIndexController;
use App\Http\Controllers\Admin\Agent\ShowController as AgentShowController;
use App\Http\Controllers\Admin\Agent\StoreController as AgentStoreController;
use App\Http\Controllers\Admin\Agent\UpdateController as AgentUpdateController;
use App\Http\Controllers\Admin\Author\CreateController as AuthorCreateController;
use App\Http\Controllers\Admin\Author\DeleteController as AuthorDeleteController;
use App\Http\Controllers\Admin\Author\EditController as AuthorEditController;
use App\Http\Controllers\Admin\Author\IndexController as AuthorIndexController;
use App\Http\Controllers\Admin\Author\ShowController as AuthorShowController;
use App\Http\Controllers\Admin\Author\StoreController as AuthorStoreController;
use App\Http\Controllers\Admin\Author\UpdateController as AuthorUpdateController;
use App\Http\Controllers\Admin\Category\CreateController as CategoryCreateController;
use App\Http\Controllers\Admin\Category\DeleteController as CategoryDeleteController;
use App\Http\Controllers\Admin\Category\EditController as CategoryEditController;
use App\Http\Controllers\Admin\Category\IndexController as CategoryIndexController;
use App\Http\Controllers\Admin\Category\ShowController as CategoryShowController;
use App\Http\Controllers\Admin\Category\StoreController as CategoryStoreController;
use App\Http\Controllers\Admin\Category\UpdateController as CategoryUpdateController;
use App\Http\Controllers\Admin\CommercialLidController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\Course\CreateController as CourseCreateController;
use App\Http\Controllers\Admin\Course\DeleteController as CourseDeleteController;
use App\Http\Controllers\Admin\Course\EditController as CourseEditController;
use App\Http\Controllers\Admin\Course\IndexController as CourseIndexController;
use App\Http\Controllers\Admin\Course\ShowController as CourseShowController;
use App\Http\Controllers\Admin\Course\StoreController as CourseStoreController;
use App\Http\Controllers\Admin\Course\UpdateController as CourseUpdateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Document\CreateController as DocumentCreateController;
use App\Http\Controllers\Admin\Document\DeleteController as DocumentDeleteController;
use App\Http\Controllers\Admin\Document\EditController as DocumentEditController;
use App\Http\Controllers\Admin\Document\IndexController as DocumentIndexController;
use App\Http\Controllers\Admin\Document\ShowController as DocumentShowController;
use App\Http\Controllers\Admin\Document\StoreController as DocumentStoreController;
use App\Http\Controllers\Admin\Document\UpdateController as DocumentUpdateController;
use App\Http\Controllers\Admin\Leveledu\CreateController as LeveleduCreateController;
use App\Http\Controllers\Admin\Leveledu\DeleteController as LeveleduDeleteController;
use App\Http\Controllers\Admin\Leveledu\EditController as LeveleduEditController;
use App\Http\Controllers\Admin\Leveledu\IndexController as LeveleduIndexController;
use App\Http\Controllers\Admin\Leveledu\ShowController as LeveleduShowController;
use App\Http\Controllers\Admin\Leveledu\StoreController as LeveleduStoreController;
use App\Http\Controllers\Admin\Leveledu\UpdateController as LeveleduUpdateController;
use App\Http\Controllers\Admin\LidController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\Main\IndexController as MainIndexController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\Region\CreateController as RegionCreateController;
use App\Http\Controllers\Admin\Region\DeleteController as RegionDeleteController;
use App\Http\Controllers\Admin\Region\EditController as RegionEditController;
use App\Http\Controllers\Admin\Region\IndexController as RegionIndexController;
use App\Http\Controllers\Admin\Region\ShowController as RegionShowController;
use App\Http\Controllers\Admin\Region\StoreController as RegionStoreController;
use App\Http\Controllers\Admin\Region\UpdateController as RegionUpdateController;
use App\Http\Controllers\Admin\Report\IndexController as ReportIndexController;
use App\Http\Controllers\Admin\Status\CreateController as StatusCreateController;
use App\Http\Controllers\Admin\Status\DeleteController as StatusDeleteController;
use App\Http\Controllers\Admin\Status\EditController as StatusEditController;
use App\Http\Controllers\Admin\Status\IndexController as StatusIndexController;
use App\Http\Controllers\Admin\Status\ShowController as StatusShowController;
use App\Http\Controllers\Admin\Status\StoreController as StatusStoreController;
use App\Http\Controllers\Admin\Status\UpdateController as StatusUpdateController;
use App\Http\Controllers\Admin\User\CreateController as UserCreateController;
use App\Http\Controllers\Admin\User\DeleteController as UserDeleteController;
use App\Http\Controllers\Admin\User\EditController as UserEditController;
use App\Http\Controllers\Admin\User\IndexController as UserIndexController;
use App\Http\Controllers\Admin\User\ShowController as UserShowController;
use App\Http\Controllers\Admin\User\StoreController as UserStoreController;
use App\Http\Controllers\Admin\User\UpdateController as UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainIndexController::class)->name('main.index');

Route::get('/getDashboard', [DashboardController::class, 'getDashboard'])
    ->name('dashboard.getDashboard');

Route::prefix('report')->name('report.')->group(function () {
    Route::get('/', ReportIndexController::class)->name('index');
    Route::get('/getReport', [ReportIndexController::class, 'getReport'])->name('getReport');
    Route::get('/getReportExcel', [ReportIndexController::class, 'getReportExcel'])->name('getReportExcel');
});

Route::invokableCrud('user', 'user', [
    'index' => UserIndexController::class,
    'create' => UserCreateController::class,
    'store' => UserStoreController::class,
    'show' => UserShowController::class,
    'edit' => UserEditController::class,
    'update' => UserUpdateController::class,
    'delete' => UserDeleteController::class,
]);

Route::invokableCrud('about', 'about', [
    'index' => AboutIndexController::class,
    'create' => AboutCreateController::class,
    'store' => AboutStoreController::class,
    'show' => AboutShowController::class,
    'edit' => AboutEditController::class,
    'update' => AboutUpdateController::class,
    'delete' => AboutDeleteController::class,
]);

Route::invokableCrud('document', 'document', [
    'index' => DocumentIndexController::class,
    'create' => DocumentCreateController::class,
    'store' => DocumentStoreController::class,
    'show' => DocumentShowController::class,
    'edit' => DocumentEditController::class,
    'update' => DocumentUpdateController::class,
    'delete' => DocumentDeleteController::class,
]);

Route::invokableCrud('course', 'course', [
    'index' => CourseIndexController::class,
    'create' => CourseCreateController::class,
    'store' => CourseStoreController::class,
    'show' => CourseShowController::class,
    'edit' => CourseEditController::class,
    'update' => CourseUpdateController::class,
    'delete' => CourseDeleteController::class,
]);

Route::invokableCrud('category', 'category', [
    'index' => CategoryIndexController::class,
    'create' => CategoryCreateController::class,
    'store' => CategoryStoreController::class,
    'show' => CategoryShowController::class,
    'edit' => CategoryEditController::class,
    'update' => CategoryUpdateController::class,
    'delete' => CategoryDeleteController::class,
]);

Route::invokableCrud('author', 'author', [
    'index' => AuthorIndexController::class,
    'create' => AuthorCreateController::class,
    'store' => AuthorStoreController::class,
    'show' => AuthorShowController::class,
    'edit' => AuthorEditController::class,
    'update' => AuthorUpdateController::class,
    'delete' => AuthorDeleteController::class,
]);

Route::invokableCrud('agent', 'agent', [
    'index' => AgentIndexController::class,
    'create' => AgentCreateController::class,
    'store' => AgentStoreController::class,
    'show' => AgentShowController::class,
    'edit' => AgentEditController::class,
    'update' => AgentUpdateController::class,
    'delete' => AgentDeleteController::class,
]);

Route::invokableCrud('leveledu', 'leveledu', [
    'index' => LeveleduIndexController::class,
    'create' => LeveleduCreateController::class,
    'store' => LeveleduStoreController::class,
    'show' => LeveleduShowController::class,
    'edit' => LeveleduEditController::class,
    'update' => LeveleduUpdateController::class,
    'delete' => LeveleduDeleteController::class,
]);

Route::invokableCrud('region', 'region', [
    'index' => RegionIndexController::class,
    'create' => RegionCreateController::class,
    'store' => RegionStoreController::class,
    'show' => RegionShowController::class,
    'edit' => RegionEditController::class,
    'update' => RegionUpdateController::class,
    'delete' => RegionDeleteController::class,
]);

Route::invokableCrud('status', 'status', [
    'index' => StatusIndexController::class,
    'create' => StatusCreateController::class,
    'store' => StatusStoreController::class,
    'show' => StatusShowController::class,
    'edit' => StatusEditController::class,
    'update' => StatusUpdateController::class,
    'delete' => StatusDeleteController::class,
]);

Route::get('/lid/getLids', [LidController::class, 'getLids'])->name('lid.getLids');
Route::get('/lid/getLidsExcel', [LidController::class, 'getLidsExcel'])->name('lid.getLidsExcel');
Route::resource('lid', LidController::class)->whereNumber('lid');

Route::get('/commerciallid/getLids', [CommercialLidController::class, 'getLids'])
    ->name('commerciallid.getLids');
Route::resource('commerciallid', CommercialLidController::class)->whereNumber('commerciallid');

Route::resource('order', OrderController::class)->whereNumber('order');
Route::resource('company', CompanyController::class)->whereNumber('company');
Route::resource('link', LinkController::class)->whereNumber('link');
