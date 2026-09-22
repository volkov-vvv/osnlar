<?php

use App\Http\Controllers\Agent\Main\IndexController as MainIndexController;
use App\Http\Controllers\Agent\Report\IndexController as ReportIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainIndexController::class)->name('main.index');
Route::get('/report', ReportIndexController::class)->name('report.index');
