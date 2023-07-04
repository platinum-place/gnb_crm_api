<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\UserController;
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
    Route::apiResources([
        'users' => UserController::class,
    ]);

    Route::middleware(['zoho.user.filter'])->group(function () {
        Route::apiResources([
            'cases' => CaseController::class,
        ]);
    });
});
