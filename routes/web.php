<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['namespace' => 'Main'],function (){
    Route::get('/', 'IndexController')->name('main.index');
});

Route::group(['namespace' => 'Course'],function (){
    Route::get('/course', 'IndexController')->name('course.index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'],function (){
    Route::group(['namespace' => 'Main'],function (){
        Route::get('/', 'IndexController')->name('admin.main.index');
    });
    Route::group(['namespace' => 'User', 'prefix' => 'user'],function (){
        Route::get('/', 'IndexController')->name('admin.user.index');
        Route::get('/create', 'CreateController')->name('admin.user.create');
        Route::post('/', 'StoreController')->name('admin.user.store');
        Route::get('/{user}', 'ShowController')->name('admin.user.show');
        Route::get('/{user}/edit', 'EditController')->name('admin.user.edit');
        Route::patch('/{user}', 'UpdateController')->name('admin.user.update');
        Route::delete('/{user}', 'DeleteController')->name('admin.user.delete');
    });
    Route::group(['namespace' => 'Course', 'prefix' => 'course'],function (){
        Route::get('/', 'IndexController')->name('admin.course.index');
        Route::get('/create', 'CreateController')->name('admin.course.create');
        Route::post('/', 'StoreController')->name('admin.course.store');
        Route::get('/{course}', 'ShowController')->name('admin.course.show');
        Route::get('/{course}/edit', 'EditController')->name('admin.course.edit');
        Route::patch('/{course}', 'UpdateController')->name('admin.course.update');
        Route::delete('/{course}', 'DeleteController')->name('admin.course.delete');
    });
    Route::group(['namespace' => 'Category', 'prefix' => 'category'],function (){
        Route::get('/', 'IndexController')->name('admin.category.index');
        Route::get('/create', 'CreateController')->name('admin.category.create');
        Route::post('/', 'StoreController')->name('admin.category.store');
        Route::get('/{category}', 'ShowController')->name('admin.category.show');
        Route::get('/{category}/edit', 'EditController')->name('admin.category.edit');
        Route::patch('/{category}', 'UpdateController')->name('admin.category.update');
        Route::delete('/{category}', 'DeleteController')->name('admin.category.delete');
    });
    Route::group(['namespace' => 'Author', 'prefix' => 'author'],function (){
        Route::get('/', 'IndexController')->name('admin.author.index');
        Route::get('/create', 'CreateController')->name('admin.author.create');
        Route::post('/', 'StoreController')->name('admin.author.store');
        Route::get('/{author}', 'ShowController')->name('admin.author.show');
        Route::get('/{author}/edit', 'EditController')->name('admin.author.edit');
        Route::patch('/{author}', 'UpdateController')->name('admin.author.update');
        Route::delete('/{author}', 'DeleteController')->name('admin.author.delete');
    });

});
