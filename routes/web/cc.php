<?php

use App\Http\Controllers\CC\DashboardController;
use App\Http\Controllers\CC\LidController;
use App\Http\Controllers\CC\Main\IndexController as MainIndexController;
use App\Http\Controllers\Common\BioLidController;
use App\Http\Controllers\Common\OrgController;
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

Route::get('biolid', [BioLidController::class, 'index'])->name('biolid.index');
Route::get('biolid/questionnaire', [BioLidController::class, 'questionnaire'])
    ->name('biolid.questionnaire');
Route::post('biolid/send-credentials-bulk', [BioLidController::class, 'sendCredentialsBulk'])
    ->name('biolid.send-credentials-bulk');
Route::post('biolid/create-users-bulk', [BioLidController::class, 'createUsersBulk'])
    ->name('biolid.create-users-bulk');
Route::get('biolid/{biolid}/edit', [BioLidController::class, 'edit'])
    ->whereNumber('biolid')
    ->name('biolid.edit');
Route::get('biolid/{biolid}', [BioLidController::class, 'show'])
    ->whereNumber('biolid')
    ->name('biolid.show');
Route::patch('biolid/{biolid}', [BioLidController::class, 'update'])
    ->whereNumber('biolid')
    ->name('biolid.update');
Route::post('biolid/{biolid}/create-user', [BioLidController::class, 'createUser'])
    ->whereNumber('biolid')
    ->name('biolid.create-user');
Route::post('biolid/{biolid}/send-credentials', [BioLidController::class, 'sendCredentials'])
    ->whereNumber('biolid')
    ->name('biolid.send-credentials');
Route::post('biolid/{biolid}/review-data', [BioLidController::class, 'reviewData'])
    ->whereNumber('biolid')
    ->name('biolid.review-data');
Route::post('biolid/{biolid}/documents/{document}/review', [BioLidController::class, 'reviewDocument'])
    ->whereNumber('biolid')
    ->whereNumber('document')
    ->name('biolid.review-document');
Route::delete('biolid/{biolid}', [BioLidController::class, 'destroy'])
    ->whereNumber('biolid')
    ->name('biolid.destroy');

Route::resource('order', OrderController::class)->whereNumber('order');
Route::resource('link', LinkController::class)->whereNumber('link');
