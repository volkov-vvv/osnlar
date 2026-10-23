<?php

use App\Http\Controllers\User\DocsController;
use App\Http\Controllers\User\DocumentsController;
use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\User\UploadStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('/documents', DocumentsController::class)->name('documents');
Route::get('/docs/{order?}', DocsController::class)->name('docs')->whereNumber('order');
Route::post('/', UploadStoreController::class)->name('upload.store');
