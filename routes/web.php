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

Route::get('/account', 'AccountController@index')->name('index');
Route::get('/accounts', 'AccountController@get');
Route::post('/account/new', 'AccountController@store');
Route::post('/account/update', 'AccountController@update');

Route::get('/home', 'HomeController@index')->name('home');


