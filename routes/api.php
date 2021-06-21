<?php

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


// Temp bypass cor config for demo purpose.
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
header('Access-Control-Allow-Origin: *');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/ping', function(){
   return "Success";
});


Route::get('/seedData', [\App\Http\Controllers\SeederController::class, 'populateData']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('urls', \App\Http\Controllers\UrlController::class, ['except' => ['store']]);
    Route::post('/urls/search', [\App\Http\Controllers\UrlController::class,"search"]);
});

Route::resource('urls', \App\Http\Controllers\UrlController::class,['only' => ['store']]);
