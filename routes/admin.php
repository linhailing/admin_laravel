<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
Route::get('/', function () {
    return "test";
});
**/

Route::get('/', 'Admin\WelcomeController@index');
Route::get('/classic/index', 'Admin\ClassicController@index');
Route::get('/classic/add', 'Admin\ClassicController@classicAdd');
Route::any('/classic/upload', 'Admin\ClassicController@classicUpload');
//sys
Route::group(['namespace' => 'Admin','prefix' => '/sys'], function (){
    Route::get('/func_list', 'SysController@funcList');
    Route::get('/func_op', 'SysController@funcOp');
    Route::post('/func_post', 'SysController@funcPost');
    Route::get('/app_op', 'SysController@appOp');
    Route::post('/app_post', 'SysController@appPost');
    Route::get('/role_list', 'SysController@roleList');
    Route::get('/role_op', 'SysController@roleOp');
});
