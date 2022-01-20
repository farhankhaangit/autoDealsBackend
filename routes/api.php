<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\LoginRegisterController;
use App\Http\Controllers\NotificationsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('user-ads/{username}', [AdsController::class, 'loadUserAds']);
    Route::post('save-ad', [AdsController::class, 'store']);
    Route::get('delete/{id}', [AdsController::class, 'deleteAd']);
    Route::post('store-notification', [NotificationsController::class, 'storeNoti']);
    Route::get('get-notifications/{username}', [NotificationsController::class, 'getNoti']);
    Route::get('mark-read/{id}', [NotificationsController::class, 'viewedNotification']);
});
Route::get('load-ads', [AdsController::class, 'loadAds']);
Route::get('ad-detail/{id}', [AdsController::class, 'adDetail']);


Route::post('register', [LoginRegisterController::class, 'register']);
Route::post('login', [LoginRegisterController::class, 'login']);
