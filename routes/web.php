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


//Route::get('register', 'Authe\RegisterController@register')->name('register');
//Route::post('register', 'Authe\RegisterController@store');

Route::get('login', 'Authe\LoginController@login')->name('login');
Route::post('login', 'Authe\LoginController@authenticate');
Route::get('logout', 'Authe\LoginController@logout')->name('logout');


Route::get('forget-password', 'Authe\ForgotPasswordController@getEmail');
Route::post('forget-password', 'Authe\ForgotPasswordController@postEmail');

Route::get('reset-password/{token}', 'Authe\ResetPasswordController@getPassword');
Route::post('reset-password', 'Authe\ResetPasswordController@updatePassword');


Route::middleware(['auth', 'login'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('home', 'Authe\LoginController@home')->name('home');
    Route::resource('customers', 'CustomerController');
    Route::resource('messages', 'MassagesController');
    Route::get('all-customers', 'CustomerController@allCustomers')->name('all-customers');
    Route::post('customers-save', 'CustomerController@customerSave')->name('customers-save');
    /**
     * volontaire route
     */
    Route::get('all-volontaires', 'VolontairesController@allVolontaires')->name('all-volontaires');
    Route::resource('volontaires', 'VolontairesController');

    /**
     * sms
     */
    Route::resource('sms', 'SmsController');
    Route::get('all-sms', 'SmsController@allSms')->name('all-sms');
    Route::get('send-sms/{id}', 'SmsController@sendSms')->name('sendsms');
    Route::get('get-sms/{id}', 'SmsController@getSms')->name('getsms');
    Route::post('smsvalide', 'SmsController@smsValide')->name('smsvalide');
    Route::post('sms-save', 'SmsController@smsSave')->name('smssave');
});

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
Auth::routes();


