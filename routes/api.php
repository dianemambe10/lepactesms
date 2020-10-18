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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['prefix' => 'v1'],function(){

    //general unauthenticated routes here

    Route::group(['prefix' => 'customers'],function(){

        Route::post('sign-up','Api\CustomerController@signUp');
        Route::post('sign-in','Api\CustomerController@signIn');
        //unauthenticated routes for customers here

        Route::group( ['middleware' => ['auth:customer','scope:customer'] ],function(){
            // authenticated customers routes here
            Route::post('dashboard','Api\CustomerController@dashboard');
            Route::post('message-send','Api\CustomerController@messageSend');
        });
    });

    Route::group(['prefix' => 'admin'],function(){

        Route::post('sign-up','Api\AdminController@signUp');
        Route::post('sign-In','Api\AdminController@signIn');
        //unauthenticated routes for customers here

        Route::group( ['middleware' => ['auth:admins','scope:admins'] ],function(){
            // authenticated staff routes here
            Route::post('dashboard','Api\AdminController@dashboard');
        });
    });

});

Route::get('sms','Api\CustomerController@smss');