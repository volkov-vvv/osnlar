<?php

use App\Http\Controllers\User\DocsController;
use App\Http\Controllers\User\DocumentsController;
use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\User\UploadStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('/documents', [DocumentsController::class, 'index'])->name('documents');
Route::get('/documents/application-template', [DocumentsController::class, 'downloadApplication'])->name('documents.application-template');
Route::get('/documents/consent-template/{kind}', [DocumentsController::class, 'downloadConsent'])
    ->whereIn('kind', ['arpm', 'osnovanie'])
    ->name('documents.consent-template');
Route::post('/documents/submit-data', [DocumentsController::class, 'submitData'])->name('documents.submit-data');
Route::post('/documents/submit-document', [DocumentsController::class, 'submitDocument'])->name('documents.submit-document');
Route::post('/documents/submit-group', [DocumentsController::class, 'submitDocumentGroup'])->name('documents.submit-group');
Route::get('/docs/{order?}', DocsController::class)->name('docs')->whereNumber('order');
Route::post('/', UploadStoreController::class)->name('upload.store');
