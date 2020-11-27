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

Route::get('/parking/availability', 'ParkingController@getAvailability');
Route::get('/parking/size', 'ParkingController@getSize');

Route::get('/subscription/availability', 'SubscriptionController@getAvailability');
Route::get('/subscription/size', 'SubscriptionController@getSize');
Route::get('/subscription/estimate-restock-date', 'SubscriptionController@estimateRestockDate');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('/parking', 'ParkingController');
    Route::get('/subscription', 'SubscriptionController@getLog');
    Route::resource('/config', 'ConfigController');
    Route::post('/config/update', 'ConfigController@updateConfig');
});

// Route::group(['middleware' => ['auth']], function () {

// });
