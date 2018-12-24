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
Route::any('/classic/post', 'Admin\ClassicController@classicPost');
Route::get('/login', 'Admin\SysController@login');
Route::any('/loginCheck', 'Admin\SysController@loginCheck');
Route::get('/logout', 'Admin\SysController@logout');
//sys
Route::group(['namespace' => 'Admin','prefix' => '/sys'], function (){
    Route::get('/func_list', 'SysController@funcList');
    Route::get('/func_op', 'SysController@funcOp');
    Route::post('/func_post', 'SysController@funcPost');
    Route::get('/app_op', 'SysController@appOp');
    Route::post('/app_post', 'SysController@appPost');
    Route::get('/role_list', 'SysController@roleList');
    Route::get('/role_op', 'SysController@roleOp');
    Route::post('/role_post', 'SysController@rolePost');
    Route::get('/admin_list', 'SysController@adminList');
    Route::get('/admin_op', 'SysController@adminOp');
    Route::post('/admin_post', 'SysController@adminPost');
});
//books
Route::group(['namespace'=>'admin','prefix'=>'/book'], function (){
    Route::get('/', 'BookController@index');
    Route::get('/book_add', 'BookController@bookAdd');
    Route::any('/book_upload', 'BookController@bookUpload');
    Route::post('post', 'BookController@bookPost');
});