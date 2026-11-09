<?php

use App\Http\Controllers\Agent\Main\IndexController as MainIndexController;
use App\Http\Controllers\Agent\Report\IndexController as ReportIndexController;
use App\Http\Controllers\Common\BioLidController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainIndexController::class)->name('main.index');
Route::get('/report', ReportIndexController::class)->name('report.index');

Route::get('biolid', [BioLidController::class, 'index'])->name('biolid.index');
Route::get('biolid/create', [BioLidController::class, 'create'])->name('biolid.create');
Route::post('biolid', [BioLidController::class, 'store'])->name('biolid.store');
Route::get('biolid/questionnaire', [BioLidController::class, 'questionnaire'])
    ->name('biolid.questionnaire');
Route::get('biolid/documents-archive', [BioLidController::class, 'documentsArchive'])
    ->name('biolid.documents-archive');
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
