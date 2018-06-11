<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


/**
 * -----------------------------------------
 * use login
 * ----------------------------------------
 */



Route::get('register/{id}', 'Auth\Users\RegisterController@form')->name('user.register.edit');
Route::post('register/confirm', 'Auth\Users\RegisterController@confirm')->name('user.register.confirm');
Route::get('register/complete', 'Auth\Users\RegisterController@complete')->name('user.register.complete');
Route::get('register/index', 'Auth\Users\RegisterController@index')->name('user.register.index');
/**
 * -----------------------------------------
 * use index, logout User
 * ----------------------------------------
 */
Route::get('posts', 'PostController@index')->name('posts.index');
Route::get('posts/form', 'PostController@form')->name('posts.form');
Route::get('posts/form/{id}', 'PostController@form')->name('posts.edit');
Route::post('posts/confirm', 'PostController@confirm')->name('posts.confirm');
Route::get('posts/complete', 'PostController@complete')->name('posts.complete');
Route::post('posts/delete', 'PostController@delete')->name('posts.delete');
Route::post('posts/approve', 'PostController@approve')->name('posts.approve');
Route::post('posts/show/{id}', 'PostController@show')->name('posts.show');

Route::get('login', 'UserController@showLoginForm')->name('user.login.form');
Route::post('login', 'UserController@login')->name('user.login');
Route::post('logout', 'UserController@logout')->name('login.logout');
Route::get('register', 'UserController@showRegisterForm')->name('user.register.form');
Route::post('register', 'UserController@register')->name('user.register');