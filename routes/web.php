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

// login
Route::get('/login', 'UserController@login')->name('login');
Route::post('/login', 'UserController@login')->name('login');

// This is the first page and other pages
// The URLs are processed by Vue.js

// middleware is used to check if the user is authenticated when visiting these pages.
// if user is not authenticated, user will be redirected to login page
Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', 'UserController@logout')->name('user.logout');

    // admin
    Route::group(['middleware' => ['admin']], function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/{vue_capture?}', function () {
                return view('admin.index');
            })->where('vue_capture', '[\/\w\.-]*');
        });
    });

    // user
    Route::get('/apcard/balance', 'UserController@getApcardBalance');
    Route::post('/apcard/topup', 'UserController@topupApcard');

    Route::get('/parking/get-state', 'ParkingController@getCarState');
    Route::get('/parking/estimate-fee', 'ParkingController@estimateFee');
    Route::post('/parking/enter', 'ParkingController@enterCarPark');
    Route::post('/parking/exit', 'ParkingController@exitCarPark');
    Route::get('/parking/records', 'ParkingController@getRecords');

    Route::get('/subscription/get-state', 'SubscriptionController@getSubsState');
    Route::post('/subscription/purchase', 'SubscriptionController@purchaseSubs');
    Route::post('/subscription/terminate', 'SubscriptionController@terminateSubs');

    Route::get('/{vue_capture?}', function () {
        return view('index');
    })->where('vue_capture', '[\/\w\.-]*');
});
