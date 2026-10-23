<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BioLidController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommercialLidController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\LeveleduController;
use App\Http\Controllers\Admin\LidController;
use App\Http\Controllers\Common\LinkController;
use App\Http\Controllers\Common\OrderController;
use App\Http\Controllers\Common\OrgController;
use App\Http\Controllers\Common\StatusController;
use App\Http\Controllers\Admin\Main\IndexController as MainIndexController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\Report\IndexController as ReportIndexController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudyGroupController;
use App\Http\Controllers\Admin\YearController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\BioeconomySettingController;
use App\Http\Controllers\Admin\FormSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainIndexController::class)->name('main.index');

Route::get('/getDashboard', [DashboardController::class, 'getDashboard'])
    ->name('dashboard.getDashboard');

Route::prefix('report')->name('report.')->group(function () {
    Route::get('/', ReportIndexController::class)->name('index');
    Route::get('/getReport', [ReportIndexController::class, 'getReport'])->name('getReport');
    Route::get('/getReportExcel', [ReportIndexController::class, 'getReportExcel'])->name('getReportExcel');
});

Route::patch('user/{user}/block', [UserController::class, 'block'])
    ->whereNumber('user')
    ->name('user.block');
Route::resource('user', UserController::class)->whereNumber('user');
Route::resource('studygroup', StudyGroupController::class)->whereNumber('studygroup');
Route::resource('about', AboutController::class)->whereNumber('about');
Route::resource('document', DocumentController::class)->whereNumber('document');
Route::resource('course', CourseController::class)->whereNumber('course');
Route::resource('category', CategoryController::class)->whereNumber('category');
Route::patch('author/{author}/publish', [AuthorController::class, 'publish'])
    ->whereNumber('author')
    ->name('author.publish');
Route::get('author/{author}/export-pdf', [AuthorController::class, 'exportPdf'])
    ->whereNumber('author')
    ->name('author.exportPdf');
Route::resource('author', AuthorController::class)->whereNumber('author');
Route::get('contract', [ContractController::class, 'index'])->name('contract.index');
Route::get('contract/create', [ContractController::class, 'create'])->name('contract.create');
Route::post('contract', [ContractController::class, 'store'])->name('contract.store');
Route::get('contract/{contract}/generate', [ContractController::class, 'generate'])
    ->whereNumber('contract')
    ->name('contract.generate');
Route::get('contract/{contract}', [ContractController::class, 'show'])
    ->whereNumber('contract')
    ->name('contract.show');
Route::get('contract/{contract}/edit', [ContractController::class, 'edit'])
    ->whereNumber('contract')
    ->name('contract.edit');
Route::patch('contract/{contract}', [ContractController::class, 'update'])
    ->whereNumber('contract')
    ->name('contract.update');
Route::delete('contract/{contract}', [ContractController::class, 'destroy'])
    ->whereNumber('contract')
    ->name('contract.destroy');
Route::resource('agent', AgentController::class)->whereNumber('agent');
Route::resource('leveledu', LeveleduController::class)->whereNumber('leveledu');
Route::resource('region', RegionController::class)->whereNumber('region');
Route::resource('year', YearController::class)->whereNumber('year');
Route::resource('project', ProjectController::class)->whereNumber('project');
Route::get('settings/forms', [FormSettingController::class, 'edit'])->name('settings.forms.edit');
Route::put('settings/forms', [FormSettingController::class, 'update'])->name('settings.forms.update');
Route::get('settings/bioeconomy', [BioeconomySettingController::class, 'edit'])->name('settings.bioeconomy.edit');
Route::put('settings/bioeconomy', [BioeconomySettingController::class, 'update'])->name('settings.bioeconomy.update');
Route::resource('status', StatusController::class)->whereNumber('status');

Route::get('/lid/getLids', [LidController::class, 'getLids'])->name('lid.getLids');
Route::get('/lid/getLidsExcel', [LidController::class, 'getLidsExcel'])->name('lid.getLidsExcel');
Route::resource('lid', LidController::class)->whereNumber('lid');

Route::get('biolid', [BioLidController::class, 'index'])->name('biolid.index');
Route::get('biolid/questionnaire', [BioLidController::class, 'questionnaire'])
    ->name('biolid.questionnaire');
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
Route::delete('biolid/{biolid}', [BioLidController::class, 'destroy'])
    ->whereNumber('biolid')
    ->name('biolid.destroy');

Route::get('/commerciallid/getLids', [CommercialLidController::class, 'getLids'])
    ->name('commerciallid.getLids');
Route::resource('commerciallid', CommercialLidController::class)->whereNumber('commerciallid');

Route::resource('order', OrderController::class)->whereNumber('order');
Route::resource('org', OrgController::class)
    ->except(['create', 'store'])
    ->whereNumber('org');
Route::resource('company', CompanyController::class)->whereNumber('company');
Route::resource('link', LinkController::class)->whereNumber('link');
