<?php

use App\Http\Controllers\NavixyController;
use App\Http\Controllers\SystrackController;
use App\Http\Controllers\Zoho\CaseController;
use App\Http\Controllers\ZohoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__ . '/auth.php';

Route::middleware([
    /** 'auth:sanctum' */
])->group(function () {
    Route::resource('cases', CaseController::class);
    Route::resource('systrack', SystrackController::class);
    Route::resource('navixy', NavixyController::class);
    Route::get('/zoho/refresh_token', [ZohoController::class, "refreshToken"])->name("zoho.refreshToken");
    Route::get('/zoho/access_token', [ZohoController::class, "accessToken"])->name("zoho.accessToken");
});
