<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('admin/login', 'AdminUserController@getLogin');
Route::post('admin/login', 'AdminUserController@postLogin');
Route::get('admin/logout', 'AdminUserController@logout');

Route::controller('password', 'RemindersController');

Route::resource('admin/user', 'AdminUserController');
Route::resource('admin/page', 'AdminPageController');

Route::get('/', function()
{
	// $user = new User;
	// $user->username = 'joshua';
	// $user->password = Hash::make('123');
	return User::all();
	return View::make('hello');
});
