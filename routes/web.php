<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/user/datatable', 'UserController@datatable')->name('user.datatable');
    Route::get('/users/select', 'UserController@select')->name('users.select');
    Route::resource('/user','UserController');

    Route::get('/document/datatable', 'DocumentController@datatable')->name('document.datatable');
    Route::resource('/document','DocumentController');

});
