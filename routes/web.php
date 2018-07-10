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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/usercheck', 'UserController@index');

Auth::routes();

#For admin, user logged in
Route::group(['middleware' => 'role:admin,user'], function () {
    Route::get('/', 'UserController@home');
	
});


