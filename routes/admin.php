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
