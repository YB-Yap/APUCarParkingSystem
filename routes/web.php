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
| Description: To define all routes
*/

// login
Route::get('/login', 'UserController@login')->name('login');
Route::post('/login', 'UserController@login')->name('login');

// middleware is used to check if the user is authenticated when visiting these pages.
// if user is not authenticated, user will be redirected to login page
Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', 'UserController@logout')->name('user.logout');

    // admin
    Route::group(['middleware' => ['admin']], function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/subscription/all-active', 'SubscriptionController@getActiveSubs');

            Route::get('/{vue_capture?}', function () {
                return view('admin.index');
            })->where('vue_capture', '[\/\w\.-]*');
        });
    });

    // user
    Route::get('/user/profile', 'UserController@getProfile');
    Route::get('/apcard/balance', 'UserController@getApcardBalance');
    Route::post('/apcard/topup', 'UserController@topupApcard');

    Route::get('/parking/state', 'ParkingController@getState');
    Route::get('/parking/estimate-fee', 'ParkingController@estimateFee');
    Route::post('/parking/enter', 'ParkingController@enterCarPark');
    Route::post('/parking/exit', 'ParkingController@exitCarPark');
    Route::get('/parking/records', 'ParkingController@getRecords');

    Route::get('/subscription/state', 'SubscriptionController@getState');
    Route::post('/subscription/state', 'SubscriptionController@getState');
    Route::post('/subscription/purchase', 'SubscriptionController@purchaseSubs');
    Route::post('/subscription/terminate', 'SubscriptionController@terminateSubs');

    Route::get('/transaction/subscription-records', 'TransactionController@getSubsRecords');

    // The URLs are processed by Vue.js
    Route::get('/{vue_capture?}', function () {
        return view('index');
    })->where('vue_capture', '[\/\w\.-]*');
});
