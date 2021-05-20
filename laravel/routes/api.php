<?php

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

Route::group(['namespace' => 'API'], function () {

    Route::post('login', 'UserController@login');

    Route::post('register', 'UserController@register');

    Route::group(['middleware' => 'auth:api'], function(){

        Route::post('logout', 'UserController@logout');

        Route::post('refresh-token', 'UserController@refreshToken');

        Route::get('details', 'UserController@details');

    });

});
















