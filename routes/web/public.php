<?php

use App\Http\Controllers\About\IndexController as AboutIndexController;
use App\Http\Controllers\Archive\IndexController as ArchiveIndexController;
use App\Http\Controllers\Archive\ShowController as ArchiveShowController;
use App\Http\Controllers\Course\IndexController as CourseIndexController;
use App\Http\Controllers\Course\ShowController as CourseShowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Lid\CreateController as LidCreateController;
use App\Http\Controllers\Lid\CreateNewController as LidCreateNewController;
use App\Http\Controllers\Lid\IndexController as LidThankController;
use App\Http\Controllers\Lid\StoreController as LidStoreController;
use App\Http\Controllers\Lid\StoreNewController as LidStoreNewController;
use App\Http\Controllers\Main\IndexController as MainIndexController;
use App\Http\Controllers\Org\CreateController as OrgCreateController;
use App\Http\Controllers\Org\IndexController as OrgThankController;
use App\Http\Controllers\Org\StoreController as OrgStoreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Page\GuideController;
use App\Http\Controllers\Page\VideoController;
use App\Http\Controllers\Services\IndexController as ServicesIndexController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site routes
|--------------------------------------------------------------------------
*/

Route::get('/', MainIndexController::class)->name('main.index');

Route::prefix('course')->name('course.')->group(function () {
    Route::get('/', [CourseIndexController::class, 'active'])->name('index');
    Route::get('/{course}', CourseShowController::class)->name('show')->whereNumber('course');
});

Route::get('/commerce', [CourseIndexController::class, 'commerce'])->name('commerce.index');
Route::get('/future', [CourseIndexController::class, 'future'])->name('future.index');

Route::prefix('archive')->name('archive.')->group(function () {
    Route::get('/', ArchiveIndexController::class)->name('index');
    Route::get('/{archive}', ArchiveShowController::class)->name('show')->whereNumber('archive');
});

Route::get('/about', AboutIndexController::class)->name('about.index');
Route::get('/services', ServicesIndexController::class)->name('services.index');
Route::get('/video', VideoController::class)->name('video');
Route::get('/guide', GuideController::class)->name('guide');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Public lead / org / order forms
|--------------------------------------------------------------------------
*/

Route::prefix('lid')->name('lid.')->group(function () {
    Route::get('/create/{selectedCourse?}', LidCreateNewController::class)->name('create');
    Route::post('/', LidStoreNewController::class)->middleware('throttle:forms')->name('store_new');
    Route::get('/thank', LidThankController::class)->name('index');

    // Legacy form (create.blade.php still posts here)
    Route::get('/create-old', LidCreateController::class)->name('create_old');
    Route::post('/store-old', LidStoreController::class)->middleware('throttle:forms')->name('store');
});

Route::prefix('org')->name('org.')->group(function () {
    Route::get('/create', OrgCreateController::class)->name('create');
    Route::post('/', OrgStoreController::class)->middleware('throttle:forms')->name('store');
    Route::get('/thank', OrgThankController::class)->name('index');
});

Route::prefix('order')->name('order.')->group(function () {
    Route::get('/create/{course}', [OrderController::class, 'create'])->name('create')->whereNumber('course');
    Route::post('/create/finish', [OrderController::class, 'store'])->middleware('throttle:forms')->name('store');
});
