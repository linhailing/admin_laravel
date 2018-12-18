<?php

use Illuminate\Http\Request;

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
/**
Route::middleware('auth:api')->get('/user', function () {
});
 **/

Route::group(['middleware'=>'api'], function (){
    Route::group(['namespace'=>'Api','prefix'=>'classic'], function (){
        Route::get('/latest','ClassicController@getClassicLatest');
        Route::get('/{index?}/next','ClassicController@getClassicNext');
        Route::get('/{index?}/previous','ClassicController@getClassicPrevious');
    });

    Route::any('/like','Api\ClassicController@like');
});