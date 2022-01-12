<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\LoginRegisterController;
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
Route::get('load-ads',[AdsController::class,'loadAds']);
Route::get('ad-detail/{id}',[AdsController::class,'adDetail']);

Route::post('register',[LoginRegisterController::class,'register']);
Route::post('login',[LoginRegisterController::class,'login']);

Route::post('save-ad',[AdsController::class,'store']);