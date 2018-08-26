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


Route::get('/usercheck', 'UserController@index');


Route::post('auth/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@redirectLogout');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/', 'Auth\LoginController@showLoginForm');
//Auth::routes();

#For admin, user logged in
Route::group(['middleware' => 'auth'], function () {
	Route::group(['middleware' => 'role:admin,user'], function () {
	    Route::get('/', 'UserController@home');
		
	});
});

Route::get('/{vue_capture?}', function () {
   return view('home');
 })->where('vue_capture', '[\/\w\.-]*');