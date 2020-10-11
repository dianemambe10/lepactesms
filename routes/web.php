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


Route::get('register', 'Authe\RegisterController@register')->name('register');
Route::post('register', 'Authe\RegisterController@store');

Route::get('login', 'Authe\LoginController@login')->name('login');
Route::post('login', 'Authe\LoginController@authenticate');
Route::get('logout', 'Authe\LoginController@logout')->name('logout');

Route::get('home', 'Authe\LoginController@home')->name('home');
Route::get('forget-password', 'Authe\ForgotPasswordController@getEmail');
Route::post('forget-password', 'Authe\ForgotPasswordController@postEmail');

Route::get('reset-password/{token}', 'Authe\ResetPasswordController@getPassword');
Route::post('reset-password', 'Authe\ResetPasswordController@updatePassword');