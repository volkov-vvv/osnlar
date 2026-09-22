<?php

use App\Http\Controllers\Api\LidCompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// External lead intake — only store is exposed; throttle abuse.
Route::post('/lids', [LidCompanyController::class, 'store'])
    ->middleware('throttle:api-lids')
    ->name('lids.store');
