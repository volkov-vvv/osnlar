<?php

use App\Http\Controllers\Lid\ExportController;
use App\Http\Controllers\Lid\ExportTestController;
use App\Http\Controllers\MaxBotWebhookController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SavedFilterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route map is split by area. Named routes are preserved for Blade/JS.
|
*/

/*
|--------------------------------------------------------------------------
| Legacy URI redirects (keep bookmarks / cached AJAX working)
|--------------------------------------------------------------------------
*/

Route::permanentRedirect('admin/admin/getDashboard', '/admin/getDashboard');
Route::permanentRedirect('cc/cc/getDashboard', '/cc/getDashboard');
Route::permanentRedirect('admin/lid/getReportExcel', '/admin/report/getReportExcel');
Route::permanentRedirect('cc/main', '/cc');
Route::permanentRedirect('agent/main', '/agent');

/*
|--------------------------------------------------------------------------
| Public site (UTM cookies)
|--------------------------------------------------------------------------
*/

Route::middleware('cookie')->group(base_path('routes/web/public.php'));

/*
|--------------------------------------------------------------------------
| Auth (registration disabled — users created via admin / order flow)
|--------------------------------------------------------------------------
*/

Auth::routes(['register' => false]);

/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/

Route::match(['GET', 'POST'], '/payments/callback', [PaymentController::class, 'callback'])
    ->middleware('throttle:webhooks')
    ->name('payment.callback');

Route::middleware('auth')->group(function () {
    Route::post('/payments/create', [PaymentController::class, 'create'])
        ->middleware('throttle:forms')
        ->name('payment.create');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
});

/*
|--------------------------------------------------------------------------
| Protected lead exports (same public URI, admin-only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/lid/export', ExportController::class)->name('lid.export');
    Route::get('/lid/testexport', ExportTestController::class)->name('lid.testexport');
});

/*
|--------------------------------------------------------------------------
| Role areas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/web/admin.php'));

Route::middleware(['auth', 'cc'])
    ->prefix('cc')
    ->name('cc.')
    ->group(base_path('routes/web/cc.php'));

Route::middleware(['auth', 'agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(base_path('routes/web/agent.php'));

Route::middleware(['auth', 'user'])
    ->prefix('user')
    ->name('user.')
    ->group(base_path('routes/web/user.php'));

/*
|--------------------------------------------------------------------------
| Authenticated utilities
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/saved-filters/save', [SavedFilterController::class, 'save'])->name('filters.save');
    Route::get('/saved-filters/get', [SavedFilterController::class, 'get'])->name('filters.get');
});

/*
|--------------------------------------------------------------------------
| External webhooks
|--------------------------------------------------------------------------
*/

Route::post('/max/webhook', MaxBotWebhookController::class)
    ->middleware('throttle:webhooks')
    ->name('max.webhook');
