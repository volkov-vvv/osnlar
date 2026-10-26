<?php

use App\Http\Controllers\User\DocsController;
use App\Http\Controllers\User\DocumentsController;
use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\User\UploadStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('/documents', [DocumentsController::class, 'index'])->name('documents');
Route::post('/documents/submit-data', [DocumentsController::class, 'submitData'])->name('documents.submit-data');
Route::post('/documents/submit-document', [DocumentsController::class, 'submitDocument'])->name('documents.submit-document');
Route::get('/docs/{order?}', DocsController::class)->name('docs')->whereNumber('order');
Route::post('/', UploadStoreController::class)->name('upload.store');
