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

Auth::routes();

// Route::get('/home', 'DashboardController@index');

// This is the first page and other pages
// The URLs are processed by Vue.js
// fix error 404 when user type the exact url manually


// Route::get('/login', 'Auth\LoginController');
// Route::get('/login', function() {
//     return view('auth.login');
// });

Route::group(['middleware' => ['auth']], function () {
    // Route::prefix('user')->name('user.')->group(function () {

    // });
    Route::get('/logout', 'Auth\LoginController@logout');

    Route::get('/{vue_capture?}', function () {
        return view('index');
    })->where('vue_capture', '[\/\w\.-]*');
});
