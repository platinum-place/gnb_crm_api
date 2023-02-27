<?php

use App\Http\Controllers\Api\NavixyController;
use App\Http\Controllers\Api\SystrackController;
use App\Http\Controllers\Zoho\CaseController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('cases', CaseController::class);
    Route::resource('navixy', NavixyController::class);
    Route::resource('systrack', SystrackController::class);
});
