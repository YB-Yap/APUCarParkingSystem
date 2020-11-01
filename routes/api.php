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

// Route::group(['prefix' => 'auth'], function () {
//     Route::post('register', 'Auth\RegisterController@action');
//     Route::post('login', 'Auth\LoginController@action');
// });

Route::get('/availability/carpark', 'ParkingController@getAvailability');

// Route::group(['middleware' => ['auth']], function () {

// });
