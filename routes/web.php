<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\SetUserCookie;


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

Route::group(['namespace' => 'Main'],function (){
    Route::get('/', 'IndexController')->name('main.index')->middleware('cookie');
});

Route::group(['namespace' => 'Course', 'prefix' => 'course', 'middleware' => ['cookie']],function (){
    Route::get('/',[App\Http\Controllers\Course\IndexController::class, 'active'])->name('course.index');
    Route::get('/{course}', 'ShowController')->name('course.show');
});

Route::group(['namespace' => 'Commerce', 'prefix' => 'commerce', 'middleware' => ['cookie']],function (){
    Route::get('/',[App\Http\Controllers\Course\IndexController::class, 'commerce'])->name('commerce.index');
});

Route::group(['namespace' => 'Archive', 'prefix' => 'archive', 'middleware' => ['cookie']],function (){
    Route::get('/', 'IndexController')->name('archive.index');
    Route::get('/{archive}', 'ShowController')->name('archive.show');
});

Route::group(['namespace' => 'Future', 'prefix' => 'future', 'middleware' => ['cookie']],function (){
    Route::get('/',[App\Http\Controllers\Course\IndexController::class, 'future'])->name('future.index');
});

Route::group(['namespace' => 'About', 'prefix' => 'about', 'middleware' => ['cookie']],function (){
    Route::get('/', 'IndexController')->name('about.index');
});

Route::group(['namespace' => 'Services', 'prefix' => 'services', 'middleware' => ['cookie']],function (){
    Route::get('/', 'IndexController')->name('services.index');
});

Route::get('/video',function () {return view('pages.video');});

Route::get('/guide',function () {return response()->file('files/2_Документация,_содержащая_описание_функциональных_характеристик.pdf');});

Route::get('/sitemap.xml', 'SitemapController@index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('cookie');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'admin']],function (){
    Route::group(['namespace' => 'Main'],function (){
        Route::get('/', 'IndexController')->name('admin.main.index');
    });
    Route::group(['namespace' => 'Report', 'prefix' => 'report'],function (){
        Route::get('/', 'IndexController')->name('admin.report.index');
    });
    Route::get('/report/getReport',[App\Http\Controllers\Admin\Report\IndexController::class, 'getReport'])->name('admin.report.getReport');
    Route::get('/lid/getReportExcel',[App\Http\Controllers\Admin\Report\IndexController::class, 'getReportExcel'])->name('admin.report.getReportExcel');

    Route::group(['namespace' => 'User', 'prefix' => 'user'],function (){
        Route::get('/', 'IndexController')->name('admin.user.index');
        Route::get('/create', 'CreateController')->name('admin.user.create');
        Route::post('/', 'StoreController')->name('admin.user.store');
        Route::get('/{user}', 'ShowController')->name('admin.user.show');
        Route::get('/{user}/edit', 'EditController')->name('admin.user.edit');
        Route::patch('/{user}', 'UpdateController')->name('admin.user.update');
        Route::delete('/{user}', 'DeleteController')->name('admin.user.delete');
    });
    Route::group(['namespace' => 'About', 'prefix' => 'about', 'middleware' => ['cookie']],function (){
        Route::get('/', 'IndexController')->name('admin.about.index');
        Route::get('/create', 'CreateController')->name('admin.about.create');
        Route::post('/', 'StoreController')->name('admin.about.store');
        Route::get('/{about}', 'ShowController')->name('admin.about.show');
        Route::get('/{about}/edit', 'EditController')->name('admin.about.edit');
        Route::patch('/{about}', 'UpdateController')->name('admin.about.update');
        Route::delete('/{about}', 'DeleteController')->name('admin.about.delete');
    });
    Route::group(['namespace' => 'Document', 'prefix' => 'document'],function (){
        Route::get('/', 'IndexController')->name('admin.document.index');
        Route::get('/create', 'CreateController')->name('admin.document.create');
        Route::post('/', 'StoreController')->name('admin.document.store');
        Route::get('/{document}', 'ShowController')->name('admin.document.show');
        Route::get('/{document}/edit', 'EditController')->name('admin.document.edit');
        Route::patch('/{document}', 'UpdateController')->name('admin.document.update');
        Route::delete('/{document}', 'DeleteController')->name('admin.document.delete');
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
    Route::group(['namespace' => 'Agent', 'prefix' => 'agent'],function (){
        Route::get('/', 'IndexController')->name('admin.agent.index');
        Route::get('/create', 'CreateController')->name('admin.agent.create');
        Route::post('/', 'StoreController')->name('admin.agent.store');
        Route::get('/{agent}', 'ShowController')->name('admin.agent.show');
        Route::get('/{agent}/edit', 'EditController')->name('admin.agent.edit');
        Route::patch('/{agent}', 'UpdateController')->name('admin.agent.update');
        Route::delete('/{agent}', 'DeleteController')->name('admin.agent.delete');
    });
    Route::group(['namespace' => 'Leveledu', 'prefix' => 'leveledu'],function (){
        Route::get('/', 'IndexController')->name('admin.leveledu.index');
        Route::get('/create', 'CreateController')->name('admin.leveledu.create');
        Route::post('/', 'StoreController')->name('admin.leveledu.store');
        Route::get('/{leveledu}', 'ShowController')->name('admin.leveledu.show');
        Route::get('/{leveledu}/edit', 'EditController')->name('admin.leveledu.edit');
        Route::patch('/{leveledu}', 'UpdateController')->name('admin.leveledu.update');
        Route::delete('/{leveledu}', 'DeleteController')->name('admin.leveledu.delete');
    });
    Route::group(['namespace' => 'Region', 'prefix' => 'region'],function (){
        Route::get('/', 'IndexController')->name('admin.region.index');
        Route::get('/create', 'CreateController')->name('admin.region.create');
        Route::post('/', 'StoreController')->name('admin.region.store');
        Route::get('/{region}', 'ShowController')->name('admin.region.show');
        Route::get('/{region}/edit', 'EditController')->name('admin.region.edit');
        Route::patch('/{region}', 'UpdateController')->name('admin.region.update');
        Route::delete('/{region}', 'DeleteController')->name('admin.region.delete');
    });

    Route::get('/lid/getLids',[App\Http\Controllers\Admin\LidController::class, 'getLids'])->name('admin.lid.getLids');
    Route::get('/lid/getLidsExcel',[App\Http\Controllers\Admin\LidController::class, 'getLidsExcel'])->name('admin.lid.getLidsExcel');

    Route::get('/admin/getDashboard',[App\Http\Controllers\Admin\DashboardController::class, 'getDashboard'])->name('admin.dashboard.getDashboard');

    Route::get('/commerciallid/getLids',[App\Http\Controllers\Admin\CommercialLidController::class, 'getLids'])->name('admin.commerciallid.getLids');

    Route::group(['as' => 'admin.'], function() {
        Route::resource('lid', LidController::class);
        Route::resource('order', OrderController::class);
        Route::resource('commerciallid', CommercialLidController::class);
        Route::resource('company', CompanyController::class);
    });




    Route::group(['as' => 'admin.'], function() {
        Route::resource('link', LinkController::class);
    });

    Route::group(['namespace' => 'Status', 'prefix' => 'status'],function (){
        Route::get('/', 'IndexController')->name('admin.status.index');
        Route::get('/create', 'CreateController')->name('admin.status.create');
        Route::post('/', 'StoreController')->name('admin.status.store');
        Route::get('/{status}', 'ShowController')->name('admin.status.show');
        Route::get('/{status}/edit', 'EditController')->name('admin.status.edit');
        Route::patch('/{status}', 'UpdateController')->name('admin.status.update');
        Route::delete('/{status}', 'DeleteController')->name('admin.status.delete');
    });

});

Route::group(['namespace' => 'CC', 'prefix' => 'cc', 'middleware' => ['auth', 'cc']],function (){
    Route::group(['namespace' => 'Main', 'prefix' => 'main'],function (){
        Route::get('/', 'IndexController')->name('cc.main.index');
    });
    Route::group(['namespace' => 'Status', 'prefix' => 'status'],function (){
        Route::get('/', 'IndexController')->name('cc.status.index');
        Route::get('/create', 'CreateController')->name('cc.status.create');
        Route::post('/', 'StoreController')->name('cc.status.store');
        Route::get('/{status}', 'ShowController')->name('cc.status.show');
        Route::get('/{status}/edit', 'EditController')->name('cc.status.edit');
        Route::patch('/{status}', 'UpdateController')->name('cc.status.update');
        Route::delete('/{status}', 'DeleteController')->name('cc.status.delete');
    });
    Route::group(['namespace' => 'Org', 'prefix' => 'org'],function (){
        Route::get('/', 'IndexController')->name('cc.org.index');
        Route::get('/{org}', 'ShowController')->name('cc.org.show');
        Route::get('/{org}/edit', 'EditController')->name('cc.org.edit');
        Route::patch('/{org}', 'UpdateController')->name('cc.org.update');
        Route::delete('/{org}', 'DeleteController')->name('cc.org.delete');
    });

    Route::get('/lid/getLids',[App\Http\Controllers\CC\LidController::class, 'getLids'])->name('cc.lid.getLids');
    Route::get('/lid/getLidsExcel',[App\Http\Controllers\CC\LidController::class, 'getLidsExcel'])->name('cc.lid.getLidsExcel');
    Route::group(['as' => 'cc.'], function() {
        Route::resource('lid', LidController::class);
        Route::resource('order', OrderController::class);
    });

    Route::group(['as' => 'cc.'], function() {
        Route::resource('link', LinkController::class);
    });

    Route::get('/cc/getDashboard',[App\Http\Controllers\Admin\DashboardController::class, 'getDashboard'])->name('cc.dashboard.getDashboard');
});

Route::group(['namespace' => 'Agent', 'prefix' => 'agent', 'middleware' => ['auth', 'agent']],function (){
    Route::group(['namespace' => 'Main', 'prefix' => 'main'],function (){
        Route::get('/', 'IndexController')->name('agent.main.index');
    });
    Route::group(['namespace' => 'Report', 'prefix' => 'report'],function (){
        Route::get('/', 'IndexController')->name('agent.report.index');
    });
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => ['auth', 'user']],function (){
    Route::get('/', 'IndexController')->name('user.index');
    Route::get('/upload/{order?}', 'UploadController')->name('user.upload');
    Route::post('/', 'UploadStoreController')->name('user.upload.store');
});

Route::group(['namespace' => 'Lid', 'prefix' => 'lid', 'middleware' => ['cookie']],function (){
    Route::get('/create-old', 'CreateController')->name('lid.create_old');
    Route::get('/create/{selectedCourse?}', 'CreateNewController')->name('lid.create');
    Route::post('/store-old', 'StoreController')->name('lid.store');
    Route::post('/', 'StoreNewController')->name('lid.store_new');
    Route::get('/thank', 'IndexController')->name('lid.index');
    Route::get('/export', 'ExportController')->name('lid.export');
    Route::get('/testexport', 'ExportTestController')->name('lid.testexport');
});

Route::group(['namespace' => 'Org', 'prefix' => 'org', 'middleware' => ['cookie']],function (){
    Route::get('/create', 'CreateController')->name('org.create');
    Route::post('/', 'StoreController')->name('org.store');
    Route::get('/thank', 'IndexController')->name('org.index');
});

Route::match(['GET', 'POST'], '/payments/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payments/create', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payment.index');
Route::group(['namespace' => 'Order', 'prefix' => 'order'],function (){
    Route::get('/create/{course}', [\App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
    Route::post('/create/finish', [\App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
});

//Route::post('/webhook', 'WebhookController@index');

