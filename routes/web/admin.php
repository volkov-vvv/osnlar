<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Common\BioLidController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommercialLidController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContactCenterController;
use App\Http\Controllers\Admin\ContractActController;
use App\Http\Controllers\Admin\ContractAssignmentController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\ContractReportController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseRankingController;
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

Route::get('/contactcenter', [ContactCenterController::class, 'index'])
    ->name('contactcenter.index');

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
Route::get('course/ranking', [CourseRankingController::class, 'index'])
    ->name('course.ranking');
Route::post('course/ranking', [CourseRankingController::class, 'update'])
    ->name('course.ranking.update');
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
Route::get('contract/{contract}/generate-docx', [ContractController::class, 'generateDocx'])
    ->whereNumber('contract')
    ->name('contract.generateDocx');
Route::post('contract/{contract}/scanned-pdf', [ContractController::class, 'uploadScan'])
    ->whereNumber('contract')
    ->name('contract.uploadScan');
Route::get('contract/{contract}/scanned-pdf', [ContractController::class, 'downloadScan'])
    ->whereNumber('contract')
    ->name('contract.downloadScan');
Route::delete('contract/{contract}/scanned-pdf', [ContractController::class, 'destroyScan'])
    ->whereNumber('contract')
    ->name('contract.destroyScan');
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

Route::get('contract/{contract}/assignment/create', [ContractAssignmentController::class, 'create'])
    ->whereNumber('contract')
    ->name('contract.assignment.create');
Route::post('contract/{contract}/assignment', [ContractAssignmentController::class, 'store'])
    ->whereNumber('contract')
    ->name('contract.assignment.store');
Route::get('assignment/{assignment}/generate', [ContractAssignmentController::class, 'generate'])
    ->whereNumber('assignment')
    ->name('assignment.generate');
Route::post('assignment/{assignment}/scanned-pdf', [ContractAssignmentController::class, 'uploadScan'])
    ->whereNumber('assignment')
    ->name('assignment.uploadScan');
Route::get('assignment/{assignment}/scanned-pdf', [ContractAssignmentController::class, 'downloadScan'])
    ->whereNumber('assignment')
    ->name('assignment.downloadScan');
Route::delete('assignment/{assignment}/scanned-pdf', [ContractAssignmentController::class, 'destroyScan'])
    ->whereNumber('assignment')
    ->name('assignment.destroyScan');
Route::get('assignment/{assignment}', [ContractAssignmentController::class, 'show'])
    ->whereNumber('assignment')
    ->name('assignment.show');
Route::get('assignment/{assignment}/edit', [ContractAssignmentController::class, 'edit'])
    ->whereNumber('assignment')
    ->name('assignment.edit');
Route::patch('assignment/{assignment}', [ContractAssignmentController::class, 'update'])
    ->whereNumber('assignment')
    ->name('assignment.update');
Route::delete('assignment/{assignment}', [ContractAssignmentController::class, 'destroy'])
    ->whereNumber('assignment')
    ->name('assignment.destroy');

Route::get('contract/{contract}/report/create', [ContractReportController::class, 'createForContract'])
    ->whereNumber('contract')
    ->name('contract.report.create');
Route::post('contract/{contract}/report', [ContractReportController::class, 'storeForContract'])
    ->whereNumber('contract')
    ->name('contract.report.store');
Route::get('assignment/{assignment}/report/create', [ContractReportController::class, 'createForAssignment'])
    ->whereNumber('assignment')
    ->name('assignment.report.create');
Route::post('assignment/{assignment}/report', [ContractReportController::class, 'storeForAssignment'])
    ->whereNumber('assignment')
    ->name('assignment.report.store');
Route::get('contract-report/{report}/generate', [ContractReportController::class, 'generate'])
    ->whereNumber('report')
    ->name('contract_report.generate');
Route::get('contract-report/{report}/generate-docx', [ContractReportController::class, 'generateDocx'])
    ->whereNumber('report')
    ->name('contract_report.generateDocx');
Route::post('contract-report/{report}/scanned-pdf', [ContractReportController::class, 'uploadScan'])
    ->whereNumber('report')
    ->name('contract_report.uploadScan');
Route::get('contract-report/{report}/scanned-pdf', [ContractReportController::class, 'downloadScan'])
    ->whereNumber('report')
    ->name('contract_report.downloadScan');
Route::delete('contract-report/{report}/scanned-pdf', [ContractReportController::class, 'destroyScan'])
    ->whereNumber('report')
    ->name('contract_report.destroyScan');
Route::get('contract-report/{report}', [ContractReportController::class, 'show'])
    ->whereNumber('report')
    ->name('contract_report.show');
Route::get('contract-report/{report}/edit', [ContractReportController::class, 'edit'])
    ->whereNumber('report')
    ->name('contract_report.edit');
Route::patch('contract-report/{report}', [ContractReportController::class, 'update'])
    ->whereNumber('report')
    ->name('contract_report.update');
Route::delete('contract-report/{report}', [ContractReportController::class, 'destroy'])
    ->whereNumber('report')
    ->name('contract_report.destroy');

Route::get('contract/{contract}/act/create', [ContractActController::class, 'createForContract'])
    ->whereNumber('contract')
    ->name('contract.act.create');
Route::post('contract/{contract}/act', [ContractActController::class, 'storeForContract'])
    ->whereNumber('contract')
    ->name('contract.act.store');
Route::get('assignment/{assignment}/act/create', [ContractActController::class, 'createForAssignment'])
    ->whereNumber('assignment')
    ->name('assignment.act.create');
Route::post('assignment/{assignment}/act', [ContractActController::class, 'storeForAssignment'])
    ->whereNumber('assignment')
    ->name('assignment.act.store');
Route::get('contract-act/{act}/generate', [ContractActController::class, 'generate'])
    ->whereNumber('act')
    ->name('contract_act.generate');
Route::get('contract-act/{act}/generate-docx', [ContractActController::class, 'generateDocx'])
    ->whereNumber('act')
    ->name('contract_act.generateDocx');
Route::post('contract-act/{act}/scanned-pdf', [ContractActController::class, 'uploadScan'])
    ->whereNumber('act')
    ->name('contract_act.uploadScan');
Route::get('contract-act/{act}/scanned-pdf', [ContractActController::class, 'downloadScan'])
    ->whereNumber('act')
    ->name('contract_act.downloadScan');
Route::delete('contract-act/{act}/scanned-pdf', [ContractActController::class, 'destroyScan'])
    ->whereNumber('act')
    ->name('contract_act.destroyScan');
Route::get('contract-act/{act}', [ContractActController::class, 'show'])
    ->whereNumber('act')
    ->name('contract_act.show');
Route::get('contract-act/{act}/edit', [ContractActController::class, 'edit'])
    ->whereNumber('act')
    ->name('contract_act.edit');
Route::patch('contract-act/{act}', [ContractActController::class, 'update'])
    ->whereNumber('act')
    ->name('contract_act.update');
Route::delete('contract-act/{act}', [ContractActController::class, 'destroy'])
    ->whereNumber('act')
    ->name('contract_act.destroy');

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
Route::post('biolid/{biolid}/arpm-status', [BioLidController::class, 'updateArpmStatus'])
    ->whereNumber('biolid')
    ->name('biolid.arpm-status');
Route::post('biolid/{biolid}/documents/{document}/review', [BioLidController::class, 'reviewDocument'])
    ->whereNumber('biolid')
    ->whereNumber('document')
    ->name('biolid.review-document');
Route::post('biolid/{biolid}/documents/{type}/upload', [BioLidController::class, 'uploadDocument'])
    ->whereNumber('biolid')
    ->where('type', '[A-Za-z0-9_]+')
    ->name('biolid.upload-document');
Route::delete('biolid/{biolid}/documents/{document}', [BioLidController::class, 'destroyDocument'])
    ->whereNumber('biolid')
    ->whereNumber('document')
    ->name('biolid.destroy-document');
Route::delete('biolid/{biolid}', [BioLidController::class, 'destroy'])
    ->whereNumber('biolid')
    ->name('biolid.destroy');

Route::get('/commerciallid/getLids', [CommercialLidController::class, 'getLids'])
    ->name('commerciallid.getLids');
Route::resource('commerciallid', CommercialLidController::class)->whereNumber('commerciallid');

Route::resource('order', OrderController::class)->whereNumber('order');
Route::patch('org/{org}/publish', [OrgController::class, 'publish'])
    ->whereNumber('org')
    ->name('org.publish');
Route::resource('org', OrgController::class)
    ->except(['create', 'store'])
    ->whereNumber('org');
Route::resource('company', CompanyController::class)->whereNumber('company');
Route::resource('link', LinkController::class)->whereNumber('link');
